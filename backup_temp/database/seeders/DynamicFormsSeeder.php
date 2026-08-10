<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DynamicForm;
use App\Models\DynamicFormSection;
use App\Models\DynamicFormQuestion;
use App\Models\Service;

class DynamicFormsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or Create a generic ServiceCategory
        $category = \App\Models\ServiceCategory::firstOrCreate(
            ['title' => 'Immigration Forms'],
            ['order_index' => 1]
        );

        // Get or Create Service for I-90
        $serviceI90 = Service::firstOrCreate(
            ['title' => 'I-90 Green Card Renewal'],
            [
                'service_category_id' => $category->id,
                'title' => 'I-90 Green Card Renewal',
                'subtitle' => 'Application to Replace Permanent Resident Card',
                'starting_price' => 540.00,
                'processing_time' => '1.5 - 12 months',
            ]
        );

        $formI90 = DynamicForm::updateOrCreate(
            ['slug' => 'i-90'],
            [
                'service_id' => $serviceI90->id,
                'name' => 'I-90 Application to Replace Permanent Resident Card',
                'description' => 'Dynamic Form for I-90'
            ]
        );

        // Delete existing sections to prevent duplication
        $formI90->sections()->delete();

        // Section 1: Personal Information
        $section1 = $formI90->sections()->create([
            'title' => 'Personal Information',
            'description' => 'Please provide your personal information as it appears on your current documentation.',
            'order' => 1
        ]);

        $section1->questions()->create([
            'question_text' => 'First Name',
            'field_name' => 'firstName',
            'field_type' => 'text',
            'is_required' => true,
            'order' => 1
        ]);

        $section1->questions()->create([
            'question_text' => 'Middle Name',
            'field_name' => 'middleName',
            'field_type' => 'text',
            'is_required' => false,
            'order' => 2
        ]);

        $section1->questions()->create([
            'question_text' => 'Last Name',
            'field_name' => 'lastName',
            'field_type' => 'text',
            'is_required' => true,
            'order' => 3
        ]);

        $section1->questions()->create([
            'question_text' => 'Date of Birth',
            'field_name' => 'dob',
            'field_type' => 'date',
            'is_required' => true,
            'order' => 4
        ]);

        $section1->questions()->create([
            'question_text' => 'A-Number (Alien Registration Number)',
            'field_name' => 'aNumber',
            'field_type' => 'text',
            'is_required' => true,
            'order' => 5
        ]);

        // Section 2: Green Card Information
        $gcSection = $formI90->sections()->create([
            'title' => 'Green Card Information',
            'description' => 'Information from your current Green Card.',
            'order' => 2
        ]);

        $gcSection->questions()->create([
            'question_text' => 'Alien Number/USCIS#',
            'field_name' => 'alienNumber',
            'field_type' => 'text',
            'is_required' => true,
            'help_text' => '[IMAGE:/assets/images/greencard-alien.svg]This is the 9-digit A#, A-Number or USCIS# issued by the US government.',
            'order' => 1
        ]);

        $uscisOnlineQ = $gcSection->questions()->create([
            'question_text' => 'USCIS Online Account Number',
            'field_name' => 'uscisOnlineAccountNumber',
            'field_type' => 'radio',
            'is_required' => true,
            'help_text' => 'This number is not the same as an Alien Number. USCIS Online Account Numbers are 12 digits and come from creating an online account with USCIS.',
            'order' => 2
        ]);
        $uscisOnlineQ->options()->createMany([
            ['option_label' => 'Yes', 'option_value' => 'Yes', 'order' => 1],
            ['option_label' => 'No', 'option_value' => 'No', 'order' => 2]
        ]);

        $gcSection->questions()->create([
            'question_text' => 'Green Card Category code',
            'field_name' => 'greenCardCategoryCode',
            'field_type' => 'text',
            'is_required' => true,
            'help_text' => '[IMAGE:/assets/images/greencard-details.svg]This is a three character code found on the Green Card. It is typically one or two letters followed by a number, sometimes just 1 letter as \'X\', example: IR2, CR6, etc.',
            'order' => 3
        ]);

        $gcSection->questions()->create([
            'question_text' => 'Green Card issue date',
            'field_name' => 'greenCardIssueDate',
            'field_type' => 'date',
            'is_required' => true,
            'help_text' => '\'Resident Since:\' on the Card',
            'order' => 4
        ]);

        $gcSection->questions()->create([
            'question_text' => 'Green Card expiration date',
            'field_name' => 'greenCardExpirationDate',
            'field_type' => 'date',
            'is_required' => false,
            'help_text' => 'No expiration date (\'Card Expires\') on your card? Leave this field blank.',
            'order' => 5
        ]);

        // Section 3: Physical Traits
        $section2 = $formI90->sections()->create([
            'title' => 'Physical Traits',
            'description' => 'Physical description for your new Green Card.',
            'order' => 3
        ]);

        $genderQ = $section2->questions()->create([
            'question_text' => 'Gender',
            'field_name' => 'gender',
            'field_type' => 'radio',
            'is_required' => true,
            'order' => 1
        ]);
        $genderQ->options()->createMany([
            ['option_label' => 'Male', 'option_value' => 'Male', 'order' => 1],
            ['option_label' => 'Female', 'option_value' => 'Female', 'order' => 2]
        ]);

        $eyeColorQ = $section2->questions()->create([
            'question_text' => 'Eye Color',
            'field_name' => 'eyeColor',
            'field_type' => 'select',
            'is_required' => true,
            'order' => 2
        ]);
        $eyeColorQ->options()->createMany([
            ['option_label' => 'Black', 'option_value' => 'Black', 'order' => 1],
            ['option_label' => 'Blue', 'option_value' => 'Blue', 'order' => 2],
            ['option_label' => 'Brown', 'option_value' => 'Brown', 'order' => 3],
            ['option_label' => 'Gray', 'option_value' => 'Gray', 'order' => 4],
            ['option_label' => 'Green', 'option_value' => 'Green', 'order' => 5],
            ['option_label' => 'Hazel', 'option_value' => 'Hazel', 'order' => 6],
            ['option_label' => 'Maroon', 'option_value' => 'Maroon', 'order' => 7],
            ['option_label' => 'Pink', 'option_value' => 'Pink', 'order' => 8],
            ['option_label' => 'Unknown/Other', 'option_value' => 'Unknown/Other', 'order' => 9]
        ]);

        $section2->questions()->create([
            'question_text' => 'Height (in Centimeters)',
            'field_name' => 'heightValue',
            'field_type' => 'text',
            'is_required' => true,
            'order' => 3
        ]);

        $section2->questions()->create([
            'question_text' => 'Weight (in Kilograms)',
            'field_name' => 'weightValue',
            'field_type' => 'text',
            'is_required' => true,
            'order' => 4
        ]);

        // Create N-400 Stub
        $serviceN400 = Service::firstOrCreate(
            ['title' => 'N-400 Naturalization'],
            [
                'service_category_id' => $category->id,
                'title' => 'N-400 Naturalization',
                'subtitle' => 'Application for Naturalization',
                'starting_price' => 760.00,
                'processing_time' => '6 - 12 months',
            ]
        );

        $formN400 = DynamicForm::updateOrCreate(
            ['slug' => 'n-400'],
            [
                'service_id' => $serviceN400->id,
                'name' => 'N-400 Application for Naturalization',
                'description' => 'Dynamic Form for N-400'
            ]
        );
        $formN400->sections()->delete();

        $n400sec1 = $formN400->sections()->create([
            'title' => 'Personal Information',
            'description' => 'Your basic personal information for Citizenship.',
            'order' => 1
        ]);
        
        $n400sec1->questions()->create([
            'question_text' => 'Full Name',
            'field_name' => 'fullName',
            'field_type' => 'text',
            'is_required' => true,
            'order' => 1
        ]);

        // Create I-130 Stub
        $serviceI130 = Service::firstOrCreate(
            ['title' => 'I-130 Family Sponsorship'],
            [
                'service_category_id' => $category->id,
                'title' => 'I-130 Family Sponsorship',
                'subtitle' => 'Petition for Alien Relative',
                'starting_price' => 675.00,
                'processing_time' => '10 - 15 months',
            ]
        );

        DynamicForm::updateOrCreate(
            ['slug' => 'i-130'],
            [
                'service_id' => $serviceI130->id,
                'name' => 'I-130 Petition for Alien Relative',
                'description' => 'Dynamic Form for I-130'
            ]
        );
    }
}
