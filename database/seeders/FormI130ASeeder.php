<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI130ASeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-130A%')->orWhere('subtitle', 'like', '%I-130A%')->first();
        if (!$service) {
            echo "Service I-130A not found. (Warning: Ensure the service exists if you want to link it)\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-130a'],
            ['name' => 'Supplemental Information for Spouse Beneficiary', 'description' => 'Form I-130A']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Information About You (The Spouse Beneficiary)
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About You (Spouse Beneficiary)', 'order' => 1]);
        $this->addQ($sec1, 'The purpose of this form is to collect additional information for a spouse beneficiary of Form I-130, Petition for Alien Relative. If your spouse is a U.S. citizen, lawful permanent resident, or non-citizen U.S. national who is filing Form I-130 on your behalf, you must complete and sign Form I-130A, Supplemental Information for Spouse Beneficiary, and submit it with the Form I-130 filed by your spouse. If you reside overseas, you still must complete Form I-130A, but you do not need to sign the form.', 'headingInstruction', 'heading');

        $this->addQ($sec1, '1. Alien Registration Number (A-Number) (if any)', 'aNumber');
        $this->addQ($sec1, '2. USCIS Online Account Number (if any)', 'uscisAccountNumber');

        $this->addQ($sec1, 'Your Full Name', 'headingFullName', 'heading');
        $this->addQ($sec1, '3.a. Family Name (Last Name)', 'familyName');
        $this->addQ($sec1, '3.b. Given Name (First Name)', 'givenName');
        $this->addQ($sec1, '3.c. Middle Name', 'middleName');

        $this->addQ($sec1, 'Address History', 'headingAddressHistory', 'heading');
        $this->addQ($sec1, 'Provide your physical addresses for the last five years, whether inside or outside the United States. Provide your current address first. If you need extra space to complete this section, use the space provided in Part 7. Additional Information.', 'headingAddressHistoryNote', 'heading');
        
        $this->addQ($sec1, 'Physical Address 1', 'headingPhysicalAddress1', 'heading');
        $this->addQ($sec1, '4.a. Street Number and Name', 'physical1Street');
        $this->addQ($sec1, '4.b. Apt. Ste. Flr.', 'physical1AptSteFlr');
        $this->addQ($sec1, '4.c. City or Town', 'physical1City');
        $this->addQ($sec1, '4.d. State', 'physical1State');
        $this->addQ($sec1, '4.e. ZIP Code', 'physical1Zip');
        $this->addQ($sec1, '4.f. Province', 'physical1Province');
        $this->addQ($sec1, '4.g. Postal Code', 'physical1PostalCode');
        $this->addQ($sec1, '4.h. Country', 'physical1Country');
        $this->addQ($sec1, '5.a. Date From (mm/dd/yyyy)', 'physical1DateFrom', 'date');
        $this->addQ($sec1, '5.b. Date To (mm/dd/yyyy)', 'physical1DateTo', 'date');

        $this->addQ($sec1, 'Physical Address 2', 'headingPhysicalAddress2', 'heading');
        $this->addQ($sec1, '6.a. Street Number and Name', 'physical2Street');
        $this->addQ($sec1, '6.b. Apt. Ste. Flr.', 'physical2AptSteFlr');
        $this->addQ($sec1, '6.c. City or Town', 'physical2City');
        $this->addQ($sec1, '6.d. State', 'physical2State');
        $this->addQ($sec1, '6.e. ZIP Code', 'physical2Zip');
        $this->addQ($sec1, '6.f. Province', 'physical2Province');
        $this->addQ($sec1, '6.g. Postal Code', 'physical2PostalCode');
        $this->addQ($sec1, '6.h. Country', 'physical2Country');
        $this->addQ($sec1, '7.a. Date From (mm/dd/yyyy)', 'physical2DateFrom', 'date');
        $this->addQ($sec1, '7.b. Date To (mm/dd/yyyy)', 'physical2DateTo', 'date');

        $this->addQ($sec1, 'Last Physical Address Outside the United States', 'headingLastPhysicalAddressAbroad', 'heading');
        $this->addQ($sec1, 'Provide your last address outside the United States of more than one year (even if listed above).', 'headingLastPhysicalAddressAbroadNote', 'heading');
        $this->addQ($sec1, '8.a. Street Number and Name', 'abroadStreet');
        $this->addQ($sec1, '8.b. Apt. Ste. Flr.', 'abroadAptSteFlr');
        $this->addQ($sec1, '8.c. City or Town', 'abroadCity');
        $this->addQ($sec1, '8.d. Province', 'abroadProvince');
        $this->addQ($sec1, '8.e. Postal Code', 'abroadPostalCode');
        $this->addQ($sec1, '8.f. Country', 'abroadCountry');
        $this->addQ($sec1, '9.a. Date From (mm/dd/yyyy)', 'abroadDateFrom', 'date');
        $this->addQ($sec1, '9.b. Date To (mm/dd/yyyy)', 'abroadDateTo', 'date');

        $this->addQ($sec1, 'Information About Parent 1', 'headingParent1', 'heading');
        $this->addQ($sec1, 'Full Name of Parent 1', 'headingParent1FullName', 'heading');
        $this->addQ($sec1, '10.a. Family Name (Maiden Name)', 'parent1FamilyName');
        $this->addQ($sec1, '10.b. Given Name (First Name)', 'parent1GivenName');
        $this->addQ($sec1, '10.c. Middle Name', 'parent1MiddleName');
        $this->addQ($sec1, '11. Date of Birth (mm/dd/yyyy)', 'parent1Dob', 'date');
        $this->addQ($sec1, '12. Sex', 'parent1Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec1, '13. City/Town/Village of Birth', 'parent1CityOfBirth');
        $this->addQ($sec1, '14. Country of Birth', 'parent1CountryOfBirth');
        $this->addQ($sec1, '15. City/Town/Village of Residence', 'parent1CityOfResidence');
        $this->addQ($sec1, '16. Country of Residence', 'parent1CountryOfResidence');

        $this->addQ($sec1, 'Information About Parent 2', 'headingParent2', 'heading');
        $this->addQ($sec1, 'Full Name of Parent 2', 'headingParent2FullName', 'heading');
        $this->addQ($sec1, '17.a. Family Name (Last Name)', 'parent2FamilyName');
        $this->addQ($sec1, '17.b. Given Name (First Name)', 'parent2GivenName');
        $this->addQ($sec1, '17.c. Middle Name', 'parent2MiddleName');
        $this->addQ($sec1, '18. Date of Birth (mm/dd/yyyy)', 'parent2Dob', 'date');
        $this->addQ($sec1, '19. Sex', 'parent2Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec1, '20. City/Town/Village of Birth', 'parent2CityOfBirth');
        $this->addQ($sec1, '21. Country of Birth', 'parent2CountryOfBirth');
        $this->addQ($sec1, '22. City/Town/Village of Residence', 'parent2CityOfResidence');
        $this->addQ($sec1, '23. Country of Residence', 'parent2CountryOfResidence');

        // Part 2. Information About Your Employment
        $sec2 = $form->sections()->create(['title' => 'Part 2. Information About Your Employment', 'order' => 2]);
        $this->addQ($sec2, 'Employment History', 'headingEmploymentHistory', 'heading');
        $this->addQ($sec2, 'Provide your employment history for the last five years, whether inside or outside the United States. Provide your current employment first. If you are currently unemployed, type or print "Unemployed" in Item Number 1. below. If you need extra space to complete this section, use the space provided in Part 7. Additional Information.', 'headingEmploymentHistoryNote', 'heading');
        
        $this->addQ($sec2, 'Employer 1', 'headingEmployer1', 'heading');
        $this->addQ($sec2, '1. Name of Employer/Company', 'emp1Name');
        $this->addQ($sec2, '2.a. Street Number and Name', 'emp1Street');
        $this->addQ($sec2, '2.b. Apt. Ste. Flr.', 'emp1AptSteFlr');
        $this->addQ($sec2, '2.c. City or Town', 'emp1City');
        $this->addQ($sec2, '2.d. State', 'emp1State');
        $this->addQ($sec2, '2.e. ZIP Code', 'emp1Zip');
        $this->addQ($sec2, '2.f. Province', 'emp1Province');
        $this->addQ($sec2, '2.g. Postal Code', 'emp1PostalCode');
        $this->addQ($sec2, '2.h. Country', 'emp1Country');
        $this->addQ($sec2, '3. Your Occupation', 'emp1Occupation');
        $this->addQ($sec2, '4.a. Date From (mm/dd/yyyy)', 'emp1DateFrom', 'date');
        $this->addQ($sec2, '4.b. Date To (mm/dd/yyyy)', 'emp1DateTo', 'date');

        $this->addQ($sec2, 'Employer 2', 'headingEmployer2', 'heading');
        $this->addQ($sec2, '5. Name of Employer/Company', 'emp2Name');
        $this->addQ($sec2, '6.a. Street Number and Name', 'emp2Street');
        $this->addQ($sec2, '6.b. Apt. Ste. Flr.', 'emp2AptSteFlr');
        $this->addQ($sec2, '6.c. City or Town', 'emp2City');
        $this->addQ($sec2, '6.d. State', 'emp2State');
        $this->addQ($sec2, '6.e. ZIP Code', 'emp2Zip');
        $this->addQ($sec2, '6.f. Province', 'emp2Province');
        $this->addQ($sec2, '6.g. Postal Code', 'emp2PostalCode');
        $this->addQ($sec2, '6.h. Country', 'emp2Country');
        $this->addQ($sec2, '7. Your Occupation', 'emp2Occupation');
        $this->addQ($sec2, '8.a. Date From (mm/dd/yyyy)', 'emp2DateFrom', 'date');
        $this->addQ($sec2, '8.b. Date To (mm/dd/yyyy)', 'emp2DateTo', 'date');

        // Part 3. Information About Your Employment Outside the United States
        $sec3 = $form->sections()->create(['title' => 'Part 3. Information About Your Employment Outside the United States', 'order' => 3]);
        $this->addQ($sec3, 'Provide your last occupation outside the United States if not shown above. If you never worked outside the United States, provide this information in the space provided in Part 7. Additional Information.', 'headingEmploymentAbroadNote', 'heading');
        $this->addQ($sec3, '1. Name of Employer/Company', 'abroadEmpName');
        $this->addQ($sec3, '2.a. Street Number and Name', 'abroadEmpStreet');
        $this->addQ($sec3, '2.b. Apt. Ste. Flr.', 'abroadEmpAptSteFlr');
        $this->addQ($sec3, '2.c. City or Town', 'abroadEmpCity');
        $this->addQ($sec3, '2.d. State', 'abroadEmpState');
        $this->addQ($sec3, '2.e. ZIP Code', 'abroadEmpZip');
        $this->addQ($sec3, '2.f. Province', 'abroadEmpProvince');
        $this->addQ($sec3, '2.g. Postal Code', 'abroadEmpPostalCode');
        $this->addQ($sec3, '2.h. Country', 'abroadEmpCountry');
        $this->addQ($sec3, '3. Your Occupation', 'abroadEmpOccupation');
        $this->addQ($sec3, '4.a. Date From (mm/dd/yyyy)', 'abroadEmpDateFrom', 'date');
        $this->addQ($sec3, '4.b. Date To (mm/dd/yyyy)', 'abroadEmpDateTo', 'date');

        // Part 4. Spouse Beneficiary's Statement, Contact Information, Certification, and Signature
        $sec4 = $form->sections()->create(['title' => 'Part 4. Spouse Beneficiary\'s Statement, Contact Information, Certification, and Signature', 'order' => 4]);
        
        $this->addQ($sec4, 'Spouse Beneficiary\'s Statement', 'headingSpouseStatement', 'heading');
        $this->addQ($sec4, 'NOTE: Select the box for either Item Number 1.a. or 1.b. If applicable, select the box for Item Number 2.', 'headingStatementNote', 'heading');
        $this->addQ($sec4, '1.a. I can read and understand English, and I have read and understand every question and instruction on this form and my answer to every question.', 'statementEnglish', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '1.b. The interpreter named in Part 5. read to me every question and instruction on this form and my answer to every question in:', 'statementInterpreterLanguage');
        $this->addQ($sec4, '2. At my request, the preparer name in Part 6., prepared this form for me based only upon information I provided or authorized.', 'statementPreparer', 'radio', ['Yes', 'No']);

        $this->addQ($sec4, 'Spouse Beneficiary\'s Contact Information', 'headingSpouseContact', 'heading');
        $this->addQ($sec4, '3. Spouse Beneficiary\'s Daytime Telephone Number', 'spouseDaytimePhone');
        $this->addQ($sec4, '4. Spouse Beneficiary\'s Mobile Telephone Number (if any)', 'spouseMobilePhone');
        $this->addQ($sec4, '5. Spouse Beneficiary\'s Email Address (if any)', 'spouseEmailAddress');

        $this->addQ($sec4, 'Spouse Beneficiary\'s Certification', 'headingSpouseCertification', 'heading');
        $this->addQ($sec4, 'Copies of any documents I have submitted are exact photocopies of unaltered, original documents...', 'headingSpouseCertText', 'heading');

        $this->addQ($sec4, 'Spouse Beneficiary\'s Signature', 'headingSpouseSignature', 'heading');
        $this->addQ($sec4, '6.a. Spouse Beneficiary\'s Signature (sign in ink)', 'spouseSignature');
        $this->addQ($sec4, '6.b. Date of Signature (mm/dd/yyyy)', 'spouseSignatureDate', 'date');


        // Part 5. Interpreter's Contact Information, Certification, and Signature
        $sec5 = $form->sections()->create(['title' => 'Part 5. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 5]);
        $this->addQ($sec5, 'Provide the following information about the interpreter you used to complete Form I-130A if he or she is different from the interpreter used to complete the Form I-130 filed on your behalf.', 'headingInterpreterNote', 'heading');
        $this->addQ($sec5, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec5, '1.a. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec5, '1.b. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec5, '2. Interpreter\'s Business or Organization Name (if any)', 'interpreterBusiness');

        $this->addQ($sec5, 'Interpreter\'s Mailing Address', 'headingInterpreterMailing', 'heading');
        $this->addQ($sec5, '3.a. Street Number and Name', 'interpreterMailingStreet');
        $this->addQ($sec5, '3.b. Apt. Ste. Flr.', 'interpreterMailingAptSteFlr');
        $this->addQ($sec5, '3.c. City or Town', 'interpreterMailingCity');
        $this->addQ($sec5, '3.d. State', 'interpreterMailingState');
        $this->addQ($sec5, '3.e. ZIP Code', 'interpreterMailingZip');
        $this->addQ($sec5, '3.f. Province', 'interpreterMailingProvince');
        $this->addQ($sec5, '3.g. Postal Code', 'interpreterMailingPostalCode');
        $this->addQ($sec5, '3.h. Country', 'interpreterMailingCountry');

        $this->addQ($sec5, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec5, '4. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec5, '5. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobilePhone');
        $this->addQ($sec5, '6. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');

        $this->addQ($sec5, 'Interpreter\'s Certification', 'headingInterpreterCertification', 'heading');
        $this->addQ($sec5, 'I certify, under penalty of perjury, that: I am fluent in English and', 'interpreterLanguage');

        $this->addQ($sec5, 'Interpreter\'s Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec5, '7.a. Interpreter\'s Signature (sign in ink)', 'interpreterSignature');
        $this->addQ($sec5, '7.b. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');


        // Part 6. Preparer's Contact Information, Declaration, and Signature
        $sec6 = $form->sections()->create(['title' => 'Part 6. Contact Information, Declaration, and Signature of the Person Preparing this Form, if Other Than the Spouse Beneficiary', 'order' => 6]);
        $this->addQ($sec6, 'Provide the following information about the preparer you used to complete Form I-130A if he or she is different from the preparer used to complete the Form I-130 filed on your behalf.', 'headingPreparerNote', 'heading');
        $this->addQ($sec6, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec6, '1.a. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec6, '1.b. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec6, '2. Preparer\'s Business or Organization Name (if any)', 'preparerBusiness');

        $this->addQ($sec6, 'Preparer\'s Mailing Address', 'headingPreparerMailing', 'heading');
        $this->addQ($sec6, '3.a. Street Number and Name', 'preparerMailingStreet');
        $this->addQ($sec6, '3.b. Apt. Ste. Flr.', 'preparerMailingAptSteFlr');
        $this->addQ($sec6, '3.c. City or Town', 'preparerMailingCity');
        $this->addQ($sec6, '3.d. State', 'preparerMailingState');
        $this->addQ($sec6, '3.e. ZIP Code', 'preparerMailingZip');
        $this->addQ($sec6, '3.f. Province', 'preparerMailingProvince');
        $this->addQ($sec6, '3.g. Postal Code', 'preparerMailingPostalCode');
        $this->addQ($sec6, '3.h. Country', 'preparerMailingCountry');

        $this->addQ($sec6, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec6, '4. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec6, '5. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobilePhone');
        $this->addQ($sec6, '6. Preparer\'s Email Address (if any)', 'preparerEmailAddress');

        $this->addQ($sec6, 'Preparer\'s Statement', 'headingPreparerStatement', 'heading');
        $this->addQ($sec6, '7.a. I am not an attorney or accredited representative but have prepared this form on behalf of the spouse beneficiary and with the spouse beneficiary\'s consent.', 'preparerStatementNotAttorney', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '7.b. I am an attorney or accredited representative and my representation of the spouse beneficiary in this case', 'preparerStatementAttorney', 'radio', ['extends', 'does not extend']);

        $this->addQ($sec6, 'Preparer\'s Certification', 'headingPreparerCertification', 'heading');
        $this->addQ($sec6, 'By my signature, I certify, under penalty of perjury, that I prepared this form at the request of the spouse beneficiary...', 'headingPreparerCertText', 'heading');

        $this->addQ($sec6, 'Preparer\'s Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec6, '8.a. Preparer\'s Signature (sign in ink)', 'preparerSignature');
        $this->addQ($sec6, '8.b. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');


        // Part 7. Additional Information
        $sec7 = $form->sections()->create(['title' => 'Part 7. Additional Information', 'order' => 7]);
        $this->addQ($sec7, '1.a Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec7, '1.b. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec7, '1.c. Middle Name', 'additionalMiddleName');
        $this->addQ($sec7, '2. A-Number (if any)', 'additionalANumber');
        
        for ($i = 3; $i <= 7; $i++) {
            $this->addQ($sec7, "{$i}.a. Page Number", "additional{$i}Page");
            $this->addQ($sec7, "{$i}.b. Part Number", "additional{$i}Part");
            $this->addQ($sec7, "{$i}.c. Item Number", "additional{$i}Item");
            $this->addQ($sec7, "{$i}.d. Additional Information", "additional{$i}Info", 'textarea');
        }

        echo "Successfully seeded I-130A form!\n";
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
