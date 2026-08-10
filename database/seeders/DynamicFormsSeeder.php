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
                ->id,
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
                ->id,
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
                ->id,
                'name' => 'I-130 Petition for Alien Relative',
                'description' => 'Dynamic Form for I-130'
            ]
        );
// Create I-821D Stub
        $serviceI821D = Service::firstOrCreate(
            ['title' => 'DACA Renewal (Deferred Action for Childhood Arrivals)'],
            [
                'service_category_id' => $category->id,
                'title' => 'DACA Renewal (Deferred Action for Childhood Arrivals)',
                'subtitle' => 'I-821D DACA renewal application',
                'starting_price' => 299.99,
                'processing_time' => '3 - 6 months',
            ]
        );

        $formI821d = DynamicForm::updateOrCreate(
            ['slug' => 'i-821d'],
            [
                ->id,
                'name' => 'I-821D Consideration of Deferred Action for Childhood Arrivals',
                'description' => 'Dynamic Form for I-821D'
            ]
        );
$formI821d->sections()->delete();

        // Section 1: Personal Information
        $i821dSec1 = $formI821d->sections()->create([
            'title' => 'Personal Information',
            'description' => 'Information about you (Part 1).',
            'order' => 1
        ]);
$i821dSec1->questions()->create(['question_text' => 'First Name (Given Name)', 'field_name' => 'firstName', 'field_type' => 'text', 'is_required' => true, 'order' => 1]);
$i821dSec1->questions()->create(['question_text' => 'Middle Name', 'field_name' => 'middleName', 'field_type' => 'text', 'is_required' => false, 'order' => 2]);
$i821dSec1->questions()->create(['question_text' => 'Last Name (Family Name)', 'field_name' => 'lastName', 'field_type' => 'text', 'is_required' => true, 'order' => 3]);
$i821dSec1->questions()->create(['question_text' => 'Alien Registration Number (A-Number)', 'field_name' => 'aNumber', 'field_type' => 'text', 'is_required' => false, 'order' => 4]);
$i821dSec1->questions()->create(['question_text' => 'U.S. Social Security Number', 'field_name' => 'ssn', 'field_type' => 'text', 'is_required' => false, 'order' => 5]);
$i821dSec1->questions()->create(['question_text' => 'Date of Birth', 'field_name' => 'dob', 'field_type' => 'date', 'is_required' => true, 'order' => 6]);
$i821dSec1->questions()->create(['question_text' => 'Country of Birth', 'field_name' => 'countryOfBirth', 'field_type' => 'text', 'is_required' => true, 'order' => 7]);
$i821dSec1->questions()->create(['question_text' => 'Sex', 'field_name' => 'sex', 'field_type' => 'text', 'is_required' => true, 'order' => 8]);
$i821dSec1->questions()->create(['question_text' => 'Request Type (Initial or Renewal)', 'field_name' => 'requestType', 'field_type' => 'text', 'is_required' => true, 'order' => 10]);
$i821dSec1->questions()->create(['question_text' => 'DACA Expiration Date (if Renewal)', 'field_name' => 'dacaExpiresOn', 'field_type' => 'date', 'is_required' => false, 'order' => 11]);
$i821dSec1->questions()->create(['question_text' => 'Immigration Detention Status', 'field_name' => 'immigrationDetention', 'field_type' => 'text', 'is_required' => true, 'order' => 12]);
$i821dSec1->questions()->create(['question_text' => 'Have you EVER been in removal proceedings?', 'field_name' => 'removalProceedings', 'field_type' => 'text', 'is_required' => true, 'order' => 13]);
$i821dSec1->questions()->create(['question_text' => 'City/Town/Village of Birth', 'field_name' => 'cityTownBirth', 'field_type' => 'text', 'is_required' => true, 'order' => 14]);
$i821dSec1->questions()->create(['question_text' => 'Current Country of Residence', 'field_name' => 'currentCountryResidence', 'field_type' => 'text', 'is_required' => true, 'order' => 15]);
$i821dSec1->questions()->create(['question_text' => 'Country of Citizenship or Nationality', 'field_name' => 'countryCitizenship', 'field_type' => 'text', 'is_required' => true, 'order' => 16]);
$i821dSec1->questions()->create(['question_text' => 'Ethnicity', 'field_name' => 'ethnicity', 'field_type' => 'text', 'is_required' => true, 'order' => 17]);
$i821dSec1->questions()->create(['question_text' => 'Race', 'field_name' => 'race', 'field_type' => 'text', 'is_required' => true, 'order' => 18]);
$i821dSec1->questions()->create(['question_text' => 'Height (Feet and Inches)', 'field_name' => 'height', 'field_type' => 'text', 'is_required' => true, 'order' => 19]);
$i821dSec1->questions()->create(['question_text' => 'Weight (Pounds)', 'field_name' => 'weight', 'field_type' => 'text', 'is_required' => true, 'order' => 20]);
$i821dSec1->questions()->create(['question_text' => 'Eye Color', 'field_name' => 'eyeColor', 'field_type' => 'text', 'is_required' => true, 'order' => 21]);
$i821dSec1->questions()->create(['question_text' => 'Hair Color', 'field_name' => 'hairColor', 'field_type' => 'text', 'is_required' => true, 'order' => 22]);
// Section 2: Address Information
        $i821dSec2 = $formI821d->sections()->create([
            'title' => 'Address Information',
            'description' => 'U.S. Mailing Address and Current Address.',
            'order' => 2
        ]);
$i821dSec2->questions()->create(['question_text' => 'In Care Of Name', 'field_name' => 'inCareOf', 'field_type' => 'text', 'is_required' => false, 'order' => 1]);
$i821dSec2->questions()->create(['question_text' => 'Street Number and Name', 'field_name' => 'streetName', 'field_type' => 'text', 'is_required' => true, 'order' => 2]);
$i821dSec2->questions()->create(['question_text' => 'Apt/Ste/Flr', 'field_name' => 'aptSteFlr', 'field_type' => 'text', 'is_required' => false, 'order' => 3]);
$i821dSec2->questions()->create(['question_text' => 'City or Town', 'field_name' => 'city', 'field_type' => 'text', 'is_required' => true, 'order' => 4]);
$i821dSec2->questions()->create(['question_text' => 'State', 'field_name' => 'state', 'field_type' => 'text', 'is_required' => true, 'order' => 5]);
$i821dSec2->questions()->create(['question_text' => 'ZIP Code', 'field_name' => 'zipCode', 'field_type' => 'text', 'is_required' => true, 'order' => 6]);
$i821dSec2->questions()->create(['question_text' => 'I have been continuously residing in the U.S. since at least June 15, 2007, up to the present time.', 'field_name' => 'continuousResidenceSince2007', 'field_type' => 'text', 'is_required' => true, 'order' => 7]);
// Section 3: Background & Education
        $i821dSec3 = $formI821d->sections()->create([
            'title' => 'Background & Education',
            'description' => 'Initial Requests Only & Military Information.',
            'order' => 3
        ]);
$i821dSec3->questions()->create(['question_text' => 'I initially arrived and established residence in the U.S. prior to 16 years of age.', 'field_name' => 'arrivedPriorTo16', 'field_type' => 'text', 'is_required' => false, 'order' => 1]);
$i821dSec3->questions()->create(['question_text' => 'Date of Initial Entry into the United States', 'field_name' => 'dateOfInitialEntry', 'field_type' => 'date', 'is_required' => false, 'order' => 2]);
$i821dSec3->questions()->create(['question_text' => 'Place of Initial Entry into the United States', 'field_name' => 'placeOfInitialEntry', 'field_type' => 'text', 'is_required' => false, 'order' => 3]);
$i821dSec3->questions()->create(['question_text' => 'Immigration Status on June 15, 2012', 'field_name' => 'statusOnJune2012', 'field_type' => 'text', 'is_required' => false, 'order' => 4]);
$i821dSec3->questions()->create(['question_text' => 'Were you EVER issued an Arrival-Departure Record (Form I-94)?', 'field_name' => 'issuedI94', 'field_type' => 'text', 'is_required' => false, 'order' => 5]);
$i821dSec3->questions()->create(['question_text' => 'Form I-94 number (if available)', 'field_name' => 'i94Number', 'field_type' => 'text', 'is_required' => false, 'order' => 6]);
$i821dSec3->questions()->create(['question_text' => 'Date authorized stay expired', 'field_name' => 'i94Expiration', 'field_type' => 'date', 'is_required' => false, 'order' => 7]);
$i821dSec3->questions()->create(['question_text' => 'Education guideline met (e.g. Graduated, GED, Currently in school)', 'field_name' => 'educationGuideline', 'field_type' => 'text', 'is_required' => false, 'order' => 8]);
$i821dSec3->questions()->create(['question_text' => 'Name, City, and State of School', 'field_name' => 'schoolDetails', 'field_type' => 'text', 'is_required' => false, 'order' => 9]);
$i821dSec3->questions()->create(['question_text' => 'Date of Graduation / Last Attendance', 'field_name' => 'graduationDate', 'field_type' => 'date', 'is_required' => false, 'order' => 10]);
$i821dSec3->questions()->create(['question_text' => 'Were you a member of the U.S. Armed Forces or Coast Guard?', 'field_name' => 'militaryMember', 'field_type' => 'text', 'is_required' => false, 'order' => 11]);
// Section 4: Criminal, National Security, and Public Safety
        $i821dSec4 = $formI821d->sections()->create([
            'title' => 'Criminal & Public Safety',
            'description' => 'Criminal, National Security, and Public Safety Information.',
            'order' => 4
        ]);
$i821dSec4->questions()->create(['question_text' => 'EVER been arrested for, charged with, or convicted of a felony or misdemeanor in the U.S.?', 'field_name' => 'arrestedUS', 'field_type' => 'text', 'is_required' => true, 'order' => 1]);
$i821dSec4->questions()->create(['question_text' => 'EVER been arrested for, charged with, or convicted of a crime in any other country?', 'field_name' => 'arrestedForeign', 'field_type' => 'text', 'is_required' => true, 'order' => 2]);
$i821dSec4->questions()->create(['question_text' => 'EVER engaged in, continue to engage in, or plan to engage in terrorist activities?', 'field_name' => 'terroristActivities', 'field_type' => 'text', 'is_required' => true, 'order' => 3]);
$i821dSec4->questions()->create(['question_text' => 'Are you NOW or EVER been a member of a gang?', 'field_name' => 'gangMember', 'field_type' => 'text', 'is_required' => true, 'order' => 4]);
$i821dSec4->questions()->create(['question_text' => 'EVER engaged in killing, severely injuring, or sexual contact forced?', 'field_name' => 'violentActs', 'field_type' => 'text', 'is_required' => true, 'order' => 5]);
$i821dSec4->questions()->create(['question_text' => 'EVER recruited or used any person under age 15 in hostilities?', 'field_name' => 'childSoldier', 'field_type' => 'text', 'is_required' => true, 'order' => 6]);
}
}
