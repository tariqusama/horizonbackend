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
        $goalTitle = $request->input('goal');
        $answers = $request->input('answers', []);

        $goal = \App\Models\SignupGoal::where('title', $goalTitle)->with('questions')->first();

        if (!$goal) {
            return response()->json([
                'title' => "Choose Your Plan",
                'basic' => "$349.99",
                'advanced' => "$449.99",
                'premium' => "$599.99",
                'processing_time' => null,
            ]);
        }

        $serviceId = $goal->default_service_id;

        foreach ($goal->questions as $index => $question) {
            $frontendAnswerIndex = $index + 1; // Frontend answers are 1-indexed based on currentStep
            if (isset($answers[$frontendAnswerIndex]) && isset($question->service_mappings) && is_array($question->service_mappings)) {
                $userAnswer = $answers[$frontendAnswerIndex];
                if (isset($question->service_mappings[$userAnswer])) {
                    $serviceId = $question->service_mappings[$userAnswer];
                }
            }
        }

        if (!$serviceId) {
            // Fallback for missing service
            return response()->json([
                'title' => $goalTitle,
                'basic' => "$349.99",
                'advanced' => "$449.99",
                'premium' => "$599.99",
                'processing_time' => null,
            ]);
        }

        $service = \App\Models\Service::with('packages')->find($serviceId);
        
        if (!$service) {
            return response()->json([
                'title' => $goalTitle,
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
            'title' => $service->title,
            'basic' => $basic ? "$" . number_format($basic->price, 2) : "$349.99",
            'advanced' => $advanced ? "$" . number_format($advanced->price, 2) : "$449.99",
            'premium' => $premium ? "$" . number_format($premium->price, 2) : "$599.99",
            'processing_time' => $service->processing_time,
        ]);
    }
}
