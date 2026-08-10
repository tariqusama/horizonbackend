<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI864ASeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-864A%')->orWhere('subtitle', 'like', '%I-864A%')->first();
        if (!$service) {
            echo "Service I-864A not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-864a'],
            ['name' => 'Contract Between Sponsor and Household Member', 'description' => 'Form I-864A']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Top level questions
        $secIntro = $form->sections()->create(['title' => 'Form Details', 'order' => 1]);
        $this->addQ($secIntro, 'This Form I-864A relates to a household member who:', 'householdMemberType', 'radio', ['IS the intending immigrant', 'IS NOT the intending immigrant']);

        // Part 1. Information About You (the Household Member)
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About You (the Household Member)', 'order' => 2]);
        $this->addQ($sec1, 'Full Name', 'headingFullName', 'heading');
        $this->addQ($sec1, '1. Family Name (Last Name)', 'lastName');
        $this->addQ($sec1, '1. Given Name (First Name)', 'firstName');
        $this->addQ($sec1, '1. Middle Name (if applicable)', 'middleName');

        $this->addQ($sec1, 'Mailing Address', 'headingMailing', 'heading');
        $this->addQ($sec1, '2. In Care Of Name (if any)', 'mailingInCareOf');
        $this->addQ($sec1, '2. Street Number and Name', 'mailingStreet');
        $this->addQ($sec1, '2. Apt. Ste. Flr.', 'mailingAptSteFlr');
        $this->addQ($sec1, '2. City or Town', 'mailingCity');
        $this->addQ($sec1, '2. State', 'mailingState');
        $this->addQ($sec1, '2. ZIP Code', 'mailingZip');
        $this->addQ($sec1, '2. Province', 'mailingProvince');
        $this->addQ($sec1, '2. Postal Code', 'mailingPostalCode');
        $this->addQ($sec1, '2. Country', 'mailingCountry');
        $this->addQ($sec1, '3. Is your current mailing address the same as your physical address?', 'mailingSameAsPhysical', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "No" to Item Number 3., provide your physical address.', 'headingPhysicalCondition', 'heading');

        $this->addQ($sec1, 'Physical Address', 'headingPhysical', 'heading');
        $this->addQ($sec1, '4. Street Number and Name', 'physicalStreet');
        $this->addQ($sec1, '4. Apt. Ste. Flr.', 'physicalAptSteFlr');
        $this->addQ($sec1, '4. City or Town', 'physicalCity');
        $this->addQ($sec1, '4. State', 'physicalState');
        $this->addQ($sec1, '4. ZIP Code', 'physicalZip');
        $this->addQ($sec1, '4. Province', 'physicalProvince');
        $this->addQ($sec1, '4. Postal Code', 'physicalPostalCode');
        $this->addQ($sec1, '4. Country', 'physicalCountry');

        $this->addQ($sec1, 'Other Information', 'headingOther', 'heading');
        $this->addQ($sec1, '5. Date of Birth (mm/dd/yyyy)', 'dob', 'date');
        $this->addQ($sec1, '6. Alien Registration Number (A-Number) (if any)', 'aNumber');
        $this->addQ($sec1, '7. U.S. Social Security Number (if any)', 'ssn');
        $this->addQ($sec1, '8. Country of Birth', 'countryOfBirth');
        $this->addQ($sec1, '9. USCIS Online Account Number (if any)', 'uscisNumber');

        // Part 2. Your Relationship to the Sponsor
        $sec2 = $form->sections()->create(['title' => 'Part 2. Your (the Household Member\'s) Relationship to the Sponsor', 'order' => 3]);
        $this->addQ($sec2, 'Select Item Number 1., 2., or 3.', 'relationshipToSponsor', 'radio', [
            '1. I am not the intending immigrant. I am the sponsor\'s household member. I am related to the sponsor as his/her: Spouse',
            '1. I am not the intending immigrant. I am the sponsor\'s household member. I am related to the sponsor as his/her: Son or Daughter',
            '1. I am not the intending immigrant. I am the sponsor\'s household member. I am related to the sponsor as his/her: Parent',
            '1. I am not the intending immigrant. I am the sponsor\'s household member. I am related to the sponsor as his/her: Brother or Sister',
            '1. I am not the intending immigrant. I am the sponsor\'s household member. I am related to the sponsor as his/her: Other Dependent',
            '2. I am the intending immigrant and also the sponsor\'s spouse.',
            '3. I am the intending immigrant and also a member of the sponsor\'s household.'
        ]);
        $this->addQ($sec2, 'If Other Dependent (Specify)', 'otherDependentSpecify');

        // Part 3. Your Employment and Income
        $sec3 = $form->sections()->create(['title' => 'Part 3. Your (the Household Member\'s) Employment and Income', 'order' => 4]);
        $this->addQ($sec3, 'I am currently:', 'headingEmployment', 'heading');
        $this->addQ($sec3, '1. Employed as a/an', 'employedAs');
        $this->addQ($sec3, '2. Name of Employer Number 1', 'employer1Name');
        $this->addQ($sec3, '3. Name of Employer Number 2 (if applicable)', 'employer2Name');
        $this->addQ($sec3, '4. Self employed as a/an', 'selfEmployedAs');
        $this->addQ($sec3, '5. Retired Since (mm/dd/yyyy)', 'retiredSince', 'date');
        $this->addQ($sec3, '6. Unemployed since (mm/dd/yyyy)', 'unemployedSince', 'date');
        $this->addQ($sec3, '7. My current individual annual income is: $', 'individualAnnualIncome', 'number');

        // Part 4. Your Federal Income Tax Information and Assets
        $sec4 = $form->sections()->create(['title' => 'Part 4. Your (the Household Member\'s) Federal Income Tax Information and Assets', 'order' => 5]);
        $this->addQ($sec4, '1. Have you filed a Federal income tax return for each of the three most recent tax years?', 'filedTax3Years', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '2. Most Recent Tax Year', 'taxYear1');
        $this->addQ($sec4, '2. Most Recent Total Income $', 'taxIncome1', 'number');
        $this->addQ($sec4, '2. 2nd Most Recent Tax Year', 'taxYear2');
        $this->addQ($sec4, '2. 2nd Most Recent Total Income $', 'taxIncome2', 'number');
        $this->addQ($sec4, '2. 3rd Most Recent Tax Year', 'taxYear3');
        $this->addQ($sec4, '2. 3rd Most Recent Total Income $', 'taxIncome3', 'number');

        $this->addQ($sec4, 'My assets (complete only if necessary)', 'headingAssets', 'heading');
        $this->addQ($sec4, '3. Enter the balance of all cash, savings, and checking accounts. $', 'assetBalance', 'number');
        $this->addQ($sec4, '4. Enter the net cash value of real-estate holdings. $', 'assetRealEstate', 'number');
        $this->addQ($sec4, '5. Enter the cash value of all stocks, bonds, certificates of deposit, and other assets... $', 'assetStocksBonds', 'number');
        $this->addQ($sec4, '6. Add together Item Numbers 3. - 5. and enter the number here. $', 'assetTotal', 'number');

        // Part 5. Sponsor's Promise, Statement, Contact Information, Declaration, Certification, and Signature
        $sec5 = $form->sections()->create(['title' => 'Part 5. Sponsor\'s Promise, Statement, Contact Information, Declaration, Certification, and Signature', 'order' => 6]);
        $this->addQ($sec5, 'I, THE SPONSOR... promise to complete and file an affidavit of support on behalf of the following named intending immigrants.', 'headingSponsorPromise', 'heading');
        
        for ($i = 1; $i <= 4; $i++) {
            $this->addQ($sec5, "{$i}. Intending Immigrant Number {$i}", "headingImmigrant{$i}", 'heading');
            $this->addQ($sec5, "Family Name (Last Name)", "immigrant{$i}LastName");
            $this->addQ($sec5, "Given Name (First Name)", "immigrant{$i}FirstName");
            $this->addQ($sec5, "Middle Name", "immigrant{$i}MiddleName");
            $this->addQ($sec5, "Date of Birth (mm/dd/yyyy)", "immigrant{$i}Dob", 'date');
            $this->addQ($sec5, "Alien Registration Number (A-Number)", "immigrant{$i}ANumber");
            $this->addQ($sec5, "USCIS Online Account Number", "immigrant{$i}UscisNumber");
        }

        $this->addQ($sec5, 'Sponsor\'s Statement', 'headingSponsorStatement', 'heading');
        $this->addQ($sec5, 'NOTE: Select the box for either Item Number 5.a. or 5.b. If applicable, select the box for Item Number 6.', 'headingSponsorStatementNote', 'heading');
        $this->addQ($sec5, '5.a. I can read and understand English...', 'sponsorStatementEnglish', 'checkbox', ['Yes']);
        $this->addQ($sec5, '5.b. The interpreter named in Part 7. read to me every question... in language:', 'sponsorStatementLanguage');
        $this->addQ($sec5, '6. At my request, the preparer named in Part 8., prepared this contract for me...', 'sponsorStatementPreparer', 'checkbox', ['Yes']);

        $this->addQ($sec5, 'Sponsor\'s Contact Information', 'headingSponsorContact', 'heading');
        $this->addQ($sec5, '7. Sponsor\'s Daytime Telephone Number', 'sponsorDaytimePhone');
        $this->addQ($sec5, '8. Sponsor\'s Mobile Telephone Number (if any)', 'sponsorMobilePhone');
        $this->addQ($sec5, '9. Sponsor\'s Email Address (if any)', 'sponsorEmailAddress');

        $this->addQ($sec5, 'Sponsor\'s Declaration, Certification, and Signature', 'headingSponsorSignature', 'heading');
        $this->addQ($sec5, '10. Sponsor\'s Signature', 'sponsorSignature');
        $this->addQ($sec5, '10. Date of Signature (mm/dd/yyyy)', 'sponsorSignatureDate', 'date');

        // Part 6. Your (the Household Member's) Promise, Statement, Contact Information, Declaration, Certification, and Signature
        $sec6 = $form->sections()->create(['title' => 'Part 6. Your (the Household Member\'s) Promise, Statement, Contact Information, Declaration, Certification, and Signature', 'order' => 7]);
        $this->addQ($sec6, 'Print number of intending immigrants noted in Part 5.', 'numberOfIntendingImmigrants', 'number');

        $this->addQ($sec6, 'Your (the Household Member\'s) Statement', 'headingHmStatement', 'heading');
        $this->addQ($sec6, 'NOTE: Select the box for either Item Number 1.a. or 1.b. If applicable, select the box for Item Number 2.', 'headingHmStatementNote', 'heading');
        $this->addQ($sec6, '1.a I can read and understand English...', 'hmStatementEnglish', 'checkbox', ['Yes']);
        $this->addQ($sec6, '1.b The interpreter named in Part 7. read to me every question... in language:', 'hmStatementLanguage');
        $this->addQ($sec6, '2. At my request, the preparer named in Part 8., prepared this contract for me...', 'hmStatementPreparer', 'checkbox', ['Yes']);

        $this->addQ($sec6, 'Your (the Household Member\'s) Contact Information', 'headingHmContact', 'heading');
        $this->addQ($sec6, '3. Your Daytime Telephone Number', 'hmDaytimePhone');
        $this->addQ($sec6, '4. Your Mobile Telephone Number (if any)', 'hmMobilePhone');
        $this->addQ($sec6, '5. Your Email Address (if any)', 'hmEmailAddress');

        $this->addQ($sec6, 'Your (the Household Member\'s) Declaration, Certification, and Signature', 'headingHmSignature', 'heading');
        $this->addQ($sec6, '6. Your (the Household Member\'s) Printed Name', 'hmPrintedName');
        $this->addQ($sec6, '7. Your (the Household Member\'s) Signature', 'hmSignature');
        $this->addQ($sec6, '7. Date of Signature (mm/dd/yyyy)', 'hmSignatureDate', 'date');

        // Part 7. Interpreter's Contact Information, Certification, and Signature
        $sec7 = $form->sections()->create(['title' => 'Part 7. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 8]);
        $this->addQ($sec7, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec7, '1. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec7, '1. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec7, '2. Interpreter\'s Business or Organization Name (if any)', 'interpreterBusiness');
        
        $this->addQ($sec7, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec7, '3. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec7, '4. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobilePhone');
        $this->addQ($sec7, '5. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');
        
        $this->addQ($sec7, 'Interpreter\'s Certification and Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec7, 'I certify that I am fluent in English and:', 'interpreterLanguage');
        $this->addQ($sec7, '6. Interpreter\'s Signature', 'interpreterSignature');
        $this->addQ($sec7, '6. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');

        // Part 8. Contact Information, Declaration, and Signature of the Person Preparing this Contract
        $sec8 = $form->sections()->create(['title' => 'Part 8. Contact Information, Declaration, and Signature of the Person Preparing this Contract', 'order' => 9]);
        $this->addQ($sec8, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec8, '1. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec8, '1. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec8, '2. Preparer\'s Business or Organization Name (if any)', 'preparerBusiness');
        
        $this->addQ($sec8, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec8, '3. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec8, '4. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobilePhone');
        $this->addQ($sec8, '5. Preparer\'s Email Address (if any)', 'preparerEmailAddress');
        
        $this->addQ($sec8, 'Preparer\'s Certification and Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec8, '6. Preparer\'s Signature', 'preparerSignature');
        $this->addQ($sec8, '6. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');

        // Part 9. Additional Information
        $sec9 = $form->sections()->create(['title' => 'Part 9. Additional Information', 'order' => 10]);
        $this->addQ($sec9, '1. Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec9, '1. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec9, '1. Middle Name (if applicable)', 'additionalMiddleName');
        $this->addQ($sec9, '2. A-Number (if any)', 'additionalANumber');
        
        for ($i = 3; $i <= 6; $i++) {
            $this->addQ($sec9, "{$i}. Page Number", "additional{$i}Page");
            $this->addQ($sec9, "{$i}. Part Number", "additional{$i}Part");
            $this->addQ($sec9, "{$i}. Item Number", "additional{$i}Item");
            $this->addQ($sec9, "{$i}. Additional Information", "additional{$i}Info", 'textarea');
        }

        echo "Successfully seeded I-864A form!\n";
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
