<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $applications = $request->user()->applications()->latest()->get();
        return response()->json($applications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'goal' => 'required|string',
            'plan' => 'required|string',
            'amount' => 'required|numeric',
            'package_name' => 'sometimes|string',
        ]);

        $application = $request->user()->applications()->create([
            'title' => $request->goal,
            'package_name' => $request->package_name ?? $request->goal,
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
            ]
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
}
