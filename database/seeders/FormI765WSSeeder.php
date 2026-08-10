<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI765WSSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-765WS%')->orWhere('subtitle', 'like', '%I-765WS%')->first();
        if (!$service) {
            echo "Service I-765WS not found. (Warning: Ensure the service exists if you want to link it)\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-765ws'],
            ['name' => 'Form I-765 Worksheet', 'description' => 'Form I-765WS']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Your Full Name
        $sec1 = $form->sections()->create(['title' => 'Part 1. Your Full Name', 'order' => 1]);
        $this->addQ($sec1, 'If you are applying for employment authorization under the (c)(14), Deferred Action, or (c)(33), Consideration of Deferred Action for Childhood Arrivals, categories, you must complete this worksheet so we can determine whether you have an economic need to work. In the spaces provided, indicate your current annual income, your current annual expenses, and the total current value of your assets. Supporting evidence is not required, but U.S. Citizenship and Immigration Services (USCIS) will accept and review any documentation that you submit. You do not need to include other household members\' financial information to establish your own economic necessity.', 'headingInstruction', 'heading');

        $this->addQ($sec1, '1.a. Family Name (Last Name)', 'familyName');
        $this->addQ($sec1, '1.b. Given Name (First Name)', 'firstName');
        $this->addQ($sec1, '1.c. Middle Name', 'middleName');

        // Part 2. Financial Information
        $sec2 = $form->sections()->create(['title' => 'Part 2. Financial Information', 'order' => 2]);
        $this->addQ($sec2, '1. My current annual income is: $', 'annualIncome', 'number');
        $this->addQ($sec2, '2. My current annual expenses are: $', 'annualExpenses', 'number');
        $this->addQ($sec2, '3. The total current value of my assets is: $', 'assetValue', 'number');

        // Part 3. Explanation
        $sec3 = $form->sections()->create(['title' => 'Part 3. Explanation', 'order' => 3]);
        $this->addQ($sec3, 'If you would like to provide an explanation regarding your current financial information or your economic need for employment authorization, use the space below.', 'headingExplanationNote', 'heading');
        $this->addQ($sec3, 'Explanation', 'explanation', 'textarea');

        echo "Successfully seeded I-765WS form!\n";
    }

    private function addQ($section, $text, $name, $type = 'text', $options = [], $required = false) {
        static $order = 1;
        $q = $section->questions()->create([
            'question_text' => mb_substr($text, 0, 255),
            'field_name' => $name,
            'field_type' => $type,
            'is_required' => $required,
            'order' => $order++
        ]);
        foreach ($options as $idx => $opt) {
            $q->options()->create([
                'option_label' => mb_substr($opt, 0, 150),
                'option_value' => mb_substr(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $opt)), 0, 150),
                'order' => $idx + 1
            ]);
        }
    }
}