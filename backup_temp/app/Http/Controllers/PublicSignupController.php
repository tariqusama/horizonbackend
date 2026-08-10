<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SignupGoal;

class PublicSignupController extends Controller
{
    public function getPathways()
    {
        $goals = SignupGoal::with('questions')->orderBy('order_index')->get();

        $pathways = [];
        $goalImages = [];

        foreach ($goals as $goal) {
            $pathways[$goal->title] = $goal->questions->map(function ($q) {
                return [
                    'question' => $q->question_text,
                    'options' => $q->options,
                    'disqualifyingOptions' => $q->disqualifying_options,
                    'skipToEndOptions' => $q->skip_to_end_options,
                ];
            });
            $goalImages[$goal->title] = $goal->image_url;
        }

        return response()->json([
            'pathways' => $pathways,
            'goalImages' => $goalImages,
            'goals' => $goals->pluck('title') // Convenient array of titles ordered by order_index
        ]);
    }

    public function getPricing(Request $request)
    {
        $goal = $request->input('goal');
        $answers = $request->input('answers', []);

        $serviceTitle = null;

        if ($goal === "Adjust status to permanent resident / get a Green Card while in US") {
            if (($answers[4] ?? '') === "Spouse") {
                $serviceTitle = "Marriage Green Card inside the U.S. – Concurrent Filing";
            } elseif (($answers[4] ?? '') === "Parent") {
                $serviceTitle = "Parent Adjustment of Status inside the U.S. – Concurrent Filing";
            } elseif (($answers[4] ?? '') === "Child") {
                $serviceTitle = "Child Adjustment of Status inside the U.S. – Concurrent Filing";
            }
        } elseif ($goal === "Bring a fiancé(e) or spouse/relative to the U.S.") {
            if (($answers[1] ?? '') === "Spouse" || ($answers[1] ?? '') === "Fiancé(e)") {
                $serviceTitle = "Petition for a Spouse outside the U.S. – USCIS Petition only";
            } elseif (($answers[1] ?? '') === "Child/Step Child") {
                $serviceTitle = "Petition for a Child outside the U.S. – USCIS Petition only";
            } elseif (($answers[1] ?? '') === "Parent" || ($answers[1] ?? '') === "Sibling") {
                $serviceTitle = "Petition for a Parent outside the U.S. – USCIS Petition only";
            }
        } elseif ($goal === "Remove conditions on residence (marriage-based conditional LPR)") {
            $serviceTitle = "Petition to Remove Conditions on Conditional Residence";
        } elseif ($goal === "Replace or fix a Green Card") {
            $serviceTitle = "Renew or Replace Permanent Resident Card";
        } elseif ($goal === "DACA (Deferred Action) — Renewal") {
            $serviceTitle = "DACA Renewal (Deferred Action for Childhood Arrivals)";
        } elseif ($goal === "Apply for U.S. Citizenship (Naturalization)") {
            $serviceTitle = "Application for U.S. Citizenship";
        }

        $defaultPricing = [
            'title' => "Choose Your Plan",
            'basic' => "$349.99",
            'advanced' => "$449.99",
            'premium' => "$599.99"
        ];

        if (!$serviceTitle) {
            return response()->json($defaultPricing);
        }

        $service = \App\Models\Service::where('title', $serviceTitle)->with('packages')->first();

        if (!$service) {
            return response()->json([
                'title' => $serviceTitle,
                'basic' => "$349.99",
                'advanced' => "$449.99",
                'premium' => "$599.99",
                'processing_time' => null,
            ]);
        }

        $basic = $service->packages->where('name', 'Basic Package')->first();
        $advanced = $service->packages->where('name', 'Advanced Package')->first();
        $premium = $service->packages->where('name', 'Premium Package')->first();

        return response()->json([
            'title' => $serviceTitle,
            'basic' => $basic ? "$" . number_format($basic->price, 2) : "$349.99",
            'advanced' => $advanced ? "$" . number_format($advanced->price, 2) : "$449.99",
            'premium' => $premium ? "$" . number_format($premium->price, 2) : "$599.99",
            'processing_time' => $service->processing_time,
        ]);
    }
}
