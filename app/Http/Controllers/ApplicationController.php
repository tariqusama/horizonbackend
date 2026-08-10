<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        
        $applications = Application::with(['user', 'participants.user', 'invites'])
            ->where('user_id', $userId)
            ->orWhereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->get();

        // 1. Modern approach: Use service_id if available to prevent N+1 query and guarantee correct forms
        $serviceIds = $applications->pluck('service_id')->filter()->unique();
        $formsByServiceId = [];
        foreach ($serviceIds as $sId) {
            $formsByServiceId[$sId] = \App\Models\DynamicForm::whereHas('services', function ($q) use ($sId) {
                $q->where('services.id', $sId);
            })->with(['services' => function ($q) use ($sId) {
                $q->where('services.id', $sId);
            }])->get(['dynamic_forms.id', 'dynamic_forms.slug', 'dynamic_forms.name']);
        }

        // 2. Legacy fallback by form_slug (before service_id existed)
        $formSlugs = $applications->whereNull('service_id')->pluck('form_slug')->filter()->unique();
        $formsBySlug = [];
        foreach ($formSlugs as $slug) {
            $formsBySlug[$slug] = \App\Models\DynamicForm::whereHas('services', function ($q) use ($slug) {
                $q->whereHas('dynamicForms', function($q2) use ($slug) {
                     $q2->where('slug', $slug); // Removed 'is_required' to ensure it finds the service
                });
            })->with(['services' => function ($q) use ($slug) {
                $q->whereHas('dynamicForms', function($q2) use ($slug) {
                     $q2->where('slug', $slug);
                });
            }])->get(['dynamic_forms.id', 'dynamic_forms.slug', 'dynamic_forms.name']);
        }

        // 3. Fallback for older applications that match by title
        $titles = $applications->whereNull('service_id')->whereNull('form_slug')->pluck('title')->unique();
        $formsByTitle = [];
        foreach ($titles as $title) {
            $formsByTitle[$title] = \App\Models\DynamicForm::whereHas('services', function ($q) use ($title) {
                $q->where('title', $title);
            })->with(['services' => function ($q) use ($title) {
                $q->where('title', $title);
            }])->get(['dynamic_forms.id', 'dynamic_forms.slug', 'dynamic_forms.name']);
        }

        foreach ($applications as $app) {
            $answers = $app->questionnaire_answers ?? [];
            if ($app->service_id) {
                $allForms = $formsByServiceId[$app->service_id] ?? collect();
            } else if ($app->form_slug) {
                $allForms = $formsBySlug[$app->form_slug] ?? collect();
            } else {
                $allForms = $formsByTitle[$app->title] ?? collect();
            }
            
            // Because one form_slug could match multiple services, we evaluate against the pivot matching our query
            $filteredForms = $allForms->filter(function ($form) use ($answers) {
                $pivot = $form->services->first()?->pivot;
                if (!$pivot) return false;
                
                if ($pivot->is_required) {
                    return true;
                }
                
                if ($pivot->condition_code) {
                    return isset($answers[$pivot->condition_code]) && ($answers[$pivot->condition_code] === true || $answers[$pivot->condition_code] === 'true');
                }
                
                return false;
            })->map(function ($form) {
                return [
                    'slug' => $form->slug,
                    'name' => $form->name
                ];
            })->unique('slug')->values();

            $app->linked_forms = $filteredForms;
        }

        return response()->json($applications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'goal' => 'required|string',
            'plan' => 'required|string',
            'amount' => 'required|numeric',
            'package_name' => 'sometimes|string',
            'service_id' => 'nullable',
            'questionnaire' => 'nullable|array',
        ]);

        $formSlug = 'i-90'; // default fallback
        
        if ($request->has('service_id') && $request->service_id !== null) {
            $serviceId = $request->service_id;
            
            if (is_numeric($serviceId)) {
                $dynamicForm = \App\Models\DynamicForm::whereHas('services', function ($q) use ($serviceId) {
                    $q->where('services.id', $serviceId);
                })->first();
                
                if ($dynamicForm) {
                    $formSlug = $dynamicForm->slug;
                }
            } else {
                $map = [
                    'i90' => 'i-90',
                    'aos' => 'i-485',
                    'i751' => 'i-751',
                    'n400' => 'n-400',
                    'fiance_petition' => 'i-129f',
                    'spouse' => 'i-130',
                    'child' => 'i-130',
                    'parent' => 'i-130',
                    'sibling' => 'i-130',
                ];
                $formSlug = $map[$serviceId] ?? $serviceId;
            }
        }

        $application = $request->user()->applications()->create([
            'title' => $request->goal,
            'package_name' => $request->package_name ?? $request->goal,
            'form_slug' => $formSlug,
            'service_id' => (isset($serviceId) && is_numeric($serviceId)) ? $serviceId : null,
            'amount' => $request->amount,
            'paid_amount' => $request->amount, // Since they just paid
            'subtitle' => 'Plan: ' . $request->plan,
            'status' => 'Active',
            'progress' => 'Application received',
            'next_step' => 'Upload supporting documents',
            'receipt_number' => 'MSC-' . rand(100, 999) . '-' . rand(10000, 99999),
            'timeline' => [
                ['step' => 'Application received', 'description' => 'USCIS has accepted your package.', 'complete' => true],
                ['step' => 'Biometrics scheduled', 'description' => 'Waiting to schedule biometrics.', 'complete' => false],
                ['step' => 'Evidence review', 'description' => 'Your documents will be under review.', 'complete' => false],
                ['step' => 'Decision pending', 'description' => 'USCIS will issue a decision.', 'complete' => false]
            ],
            'questionnaire_answers' => $request->questionnaire ?? []
        ]);

        return response()->json([
            'message' => 'Application created successfully.',
            'application' => $application
        ]);
    }

    public function saveI90(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'aNumber' => 'required|string',
            'lastName' => 'required|string',
            'firstName' => 'required|string',
            'otherNames' => 'required|in:Yes,No',
            'dob' => 'required|string',
            'countryOfBirth' => 'required|string',
            'countryOfCitizenship' => 'required|string',
            'gender' => 'required|in:Male,Female',

            // Optional fields but keeping them in validation so they can be retrieved
            'uscisOnlineAccount' => 'nullable|string',
            'middleName' => 'nullable|string',
            'ssn' => 'nullable|string',
            'motherFirstName' => 'nullable|string',
            'fatherFirstName' => 'nullable|string',
            'classOfAdmission' => 'nullable|string',
            'dateOfAdmission' => 'nullable|string',
            'portOfAdmissionCity' => 'nullable|string',
            'portOfAdmissionState' => 'nullable|string',
        ]);

        $formData = $application->form_data ?? [];
        $formData['i90'] = $validated;

        $application->form_data = $formData;
        $application->save();

        return response()->json([
            'message' => 'I-90 Form progress saved successfully.',
            'application' => $application
        ]);
    }

    public function saveG1145(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'lastName' => 'required|string',
            'firstName' => 'required|string',
            'middleName' => 'nullable|string',
        ]);

        $formData = $application->form_data ?? [];
        $formData['g1145'] = $validated;

        $application->form_data = $formData;
        $application->save();

        return response()->json([
            'message' => 'G-1145 Form progress saved successfully.',
            'application' => $application
        ]);
    }

    public function saveI130(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'relationship' => 'required|string',
            'lastName' => 'required|string',
            'firstName' => 'required|string',
            'aNumber' => 'nullable|string',
            'uscisOnlineAccount' => 'nullable|string',
        ]);

        $formData = $application->form_data ?? [];
        $formData['i130'] = $validated;

        $application->form_data = $formData;
        $application->save();

        return response()->json([
            'message' => 'I-130 Form progress saved successfully.',
            'application' => $application
        ]);
    }

    public function saveI130A(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'aNumber' => 'nullable|string'
        ]);
        $formData = $application->form_data ?? [];
        $formData['i130a'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'I-130A Form progress saved successfully.', 'application' => $application]);
    }

    public function saveI485(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'category' => 'required|string',
            'aNumber' => 'nullable|string',
            'uscisOnlineAccount' => 'nullable|string',
            'lastName' => 'required|string',
            'firstName' => 'required|string',
        ]);
        $formData = $application->form_data ?? [];
        $formData['i485'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'I-485 Form progress saved successfully.', 'application' => $application]);
    }

    public function saveI751(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'aNumber' => 'required|string',
        ]);
        $formData = $application->form_data ?? [];
        $formData['i751'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'I-751 Form progress saved successfully.', 'application' => $application]);
    }

    public function saveI765(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'reason' => 'required|string',
        ]);
        $formData = $application->form_data ?? [];
        $formData['i765'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'I-765 Form progress saved successfully.', 'application' => $application]);
    }

    public function saveI765WS(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'currentAnnualIncome' => 'required|string',
            'currentAnnualExpenses' => 'required|string',
        ]);
        $formData = $application->form_data ?? [];
        $formData['i765ws'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'I-765WS Form progress saved successfully.', 'application' => $application]);
    }

    public function saveI821D(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'detention' => 'required|string',
            'dacaType' => 'required|string',
        ]);
        $formData = $application->form_data ?? [];
        $formData['i821d'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'I-821D Form progress saved successfully.', 'application' => $application]);
    }

    public function saveI864(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'sponsorBasis' => 'required|string',
        ]);
        $formData = $application->form_data ?? [];
        $formData['i864'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'I-864 Form progress saved successfully.', 'application' => $application]);
    }

    public function saveN400(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $validated = $request->validate([
            'eligibility' => 'required|string',
            'aNumber' => 'required|string',
            'uscisOnlineAccount' => 'nullable|string',
            'lastName' => 'required|string',
            'firstName' => 'required|string',
            'middleName' => 'nullable|string',
        ]);
        $formData = $application->form_data ?? [];
        $formData['n400'] = $validated;
        $application->form_data = $formData;
        $application->save();
        return response()->json(['message' => 'N-400 Form progress saved successfully.', 'application' => $application]);
    }

    public function submit(Request $request, $id)
    {
        $application = Application::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $application->status = 'Submitted';
        $application->progress = 'Application Submitted';
        $application->next_step = 'Case Manager Review';
        $application->save();

        return response()->json([
            'message' => 'Application submitted successfully.',
            'application' => $application
        ]);
    }

    public function getBeneficiaryInvite($token)
    {
        $application = Application::whereJsonContains('form_data->beneficiary_invite->invite_token', $token)
            ->with(['user'])
            ->first();

        if (!$application) {
            return response()->json(['message' => 'Invalid or expired invite token.'], 404);
        }

        $invite = $application->form_data['beneficiary_invite'] ?? [];

        return response()->json([
            'application' => [
                'id' => $application->id,
                'title' => $application->title,
                'package_name' => $application->package_name,
                'subtitle' => $application->subtitle,
                'receipt_number' => $application->receipt_number,
            ],
            'invite' => $invite,
        ]);
    }

    public function saveBeneficiaryInvite(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'fullName' => 'required|string',
            'dob' => 'nullable|string',
            'countryOfBirth' => 'nullable|string',
            'phone' => 'nullable|string',
            'additionalInfo' => 'nullable|string',
        ]);

        $application = Application::whereJsonContains('form_data->beneficiary_invite->invite_token', $token)
            ->with(['user'])
            ->first();

        if (!$application) {
            return response()->json(['message' => 'Invalid or expired invite token.'], 404);
        }

        $invite = $application->form_data['beneficiary_invite'] ?? [];

        if (!isset($invite['email']) || strtolower($invite['email']) !== strtolower($request->email)) {
            return response()->json(['message' => 'This email does not match the invited beneficiary email.'], 403);
        }

        $formData = $application->form_data ?? [];
        $formData['beneficiary_response'] = [
            'email' => $request->email,
            'fullName' => $request->fullName,
            'dob' => $request->dob,
            'countryOfBirth' => $request->countryOfBirth,
            'phone' => $request->phone,
            'additionalInfo' => $request->additionalInfo,
            'submitted_at' => now()->toISOString(),
        ];
        $formData['beneficiary_invite'] = array_merge($invite, [
            'status' => 'completed',
            'completed_at' => now()->toISOString(),
        ]);
        $application->form_data = $formData;
        $application->save();

        return response()->json([
            'message' => 'Beneficiary intake saved successfully.',
            'application' => $application,
        ]);
    }

    /**
     * Save dynamic form data progress for any form type (dynamic engine).
     */
    public function saveFormData(Request $request, $id)
    {
        $userId = $request->user()->id;
        $application = Application::where('id', $id)
            ->where(function($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('participants', function($q2) use ($userId) {
                      $q2->where('user_id', $userId);
                  });
            })->firstOrFail();

        $formData = $request->input('form_data');
        $currentStep = $request->input('current_step');

        if (is_array($formData)) {
            // Merge with existing form data so we don't overwrite other form sections
            $existing = $application->form_data ?? [];
            $application->form_data = array_merge($existing, $formData);
        }

        if (!is_null($currentStep)) {
            $fd = $application->form_data ?? [];
            $fd['_current_step'] = (int) $currentStep;
            $application->form_data = $fd;
        }

        $application->save();

        return response()->json([
            'message' => 'Form progress saved successfully.',
            'application' => $application
        ]);
    }
}
