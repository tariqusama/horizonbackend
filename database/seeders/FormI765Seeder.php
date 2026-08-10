<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI765Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-765%')->orWhere('subtitle', 'like', '%I-765%')->first();
        if (!$service) {
            echo "Service I-765 not found. (Warning: Ensure the service exists if you want to link it)\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-765'],
            ['name' => 'Application For Employment Authorization', 'description' => 'Form I-765']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Reason for Applying
        $sec1 = $form->sections()->create(['title' => 'Part 1. Reason for Applying', 'order' => 1]);
        $this->addQ($sec1, 'I am applying for (select only one box):', 'reasonForApplying', 'radio', [
            '1.a. Initial permission to accept employment.',
            '1.b. Replacement of lost, stolen, or damaged employment authorization document, or correction of my employment authorization document NOT DUE to U.S. Citizenship and Immigration Services (USCIS) error.',
            '1.c. Renewal of my permission to accept employment. (Attach a copy of your previous employment authorization document.)'
        ]);

        // Part 2. Information About You
        $sec2 = $form->sections()->create(['title' => 'Part 2. Information About You', 'order' => 2]);
        $this->addQ($sec2, 'Your Full Legal Name', 'headingFullLegalName', 'heading');
        $this->addQ($sec2, '1.a. Family Name (Last Name)', 'lastName');
        $this->addQ($sec2, '1.b. Given Name (First Name)', 'firstName');
        $this->addQ($sec2, '1.c. Middle Name', 'middleName');

        $this->addQ($sec2, 'Other Names Used', 'headingOtherNames', 'heading');
        $this->addQ($sec2, 'Provide all other names you have ever used, including aliases, maiden name, and nicknames. If you need extra space to complete this section, use the space provided in Part 6. Additional Information.', 'headingOtherNamesNote', 'heading');
        $this->addQ($sec2, '2.a. Family Name (Last Name)', 'otherLastName1');
        $this->addQ($sec2, '2.b. Given Name (First Name)', 'otherFirstName1');
        $this->addQ($sec2, '2.c. Middle Name', 'otherMiddleName1');
        $this->addQ($sec2, '3.a. Family Name (Last Name)', 'otherLastName2');
        $this->addQ($sec2, '3.b. Given Name (First Name)', 'otherFirstName2');
        $this->addQ($sec2, '3.c. Middle Name', 'otherMiddleName2');
        $this->addQ($sec2, '4.a. Family Name (Last Name)', 'otherLastName3');
        $this->addQ($sec2, '4.b. Given Name (First Name)', 'otherFirstName3');
        $this->addQ($sec2, '4.c. Middle Name', 'otherMiddleName3');

        $this->addQ($sec2, 'Your U.S. Mailing Address', 'headingMailingAddress', 'heading');
        $this->addQ($sec2, '5.a. In Care Of Name (if any)', 'mailingInCareOf');
        $this->addQ($sec2, '5.b. Street Number and Name', 'mailingStreet');
        $this->addQ($sec2, '5.c. Apt. Ste. Flr.', 'mailingAptSteFlr');
        $this->addQ($sec2, '5.d. City or Town', 'mailingCity');
        $this->addQ($sec2, '5.e. State', 'mailingState');
        $this->addQ($sec2, '5.f. ZIP Code', 'mailingZip');

        $this->addQ($sec2, '6. Is your current mailing address the same as your physical address?', 'mailingSameAsPhysical', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: If you answered "No" to Item Number 6., provide your physical address below.', 'headingPhysicalAddressNote', 'heading');

        $this->addQ($sec2, 'U.S. Physical Address', 'headingPhysicalAddress', 'heading');
        $this->addQ($sec2, '7.a. Street Number and Name', 'physicalStreet');
        $this->addQ($sec2, '7.b. Apt. Ste. Flr.', 'physicalAptSteFlr');
        $this->addQ($sec2, '7.c. City or Town', 'physicalCity');
        $this->addQ($sec2, '7.d. State', 'physicalState');
        $this->addQ($sec2, '7.e. ZIP Code', 'physicalZip');

        $this->addQ($sec2, 'Other Information', 'headingOtherInformation', 'heading');
        $this->addQ($sec2, '8. Alien Registration Number (A-Number) (if any)', 'aNumber');
        $this->addQ($sec2, '9. USCIS Online Account Number (if any)', 'uscisAccountNumber');
        $this->addQ($sec2, '10. Sex', 'sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec2, '11. Marital Status', 'maritalStatus', 'radio', ['Single', 'Married', 'Divorced', 'Widowed']);
        $this->addQ($sec2, '12. Have you previously filed Form I-765?', 'previouslyFiled', 'radio', ['Yes', 'No']);

        $this->addQ($sec2, '13.a. Has the Social Security Administration (SSA) ever officially issued a Social Security card to you?', 'ssaIssuedCard', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: If you answered "No" to Item Number 13.a., skip to Item Number 14. If you answered "Yes" to Item Number 13.a., provide the information requested in Item Number 13.b.', 'headingSsaNote', 'heading');
        $this->addQ($sec2, '13.b. Provide your Social Security number (SSN) (if known).', 'ssn');

        $this->addQ($sec2, '14. Do you want the SSA to issue you a Social Security card? (You must also answer "Yes" to Item Number 15., Consent for Disclosure, to receive a card.)', 'wantSsaCard', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: If you answered "No" to Item Number 14., skip to Part 2., Item Number 18.a. If you answered "Yes" to Item Number 14., you must also answer "Yes" to Item Number 15.', 'headingWantSsaNote', 'heading');

        $this->addQ($sec2, '15. Consent for Disclosure: I authorize disclosure of information from this application to the SSA as required for the purpose of assigning me an SSN and issuing me a Social Security card.', 'consentForDisclosure', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: If you answered "Yes" to Item Numbers 14. - 15., provide the information requested in Item Numbers 16.a. - 17.b.', 'headingConsentNote', 'heading');
        
        $this->addQ($sec2, 'Father\'s Name', 'headingFatherName', 'heading');
        $this->addQ($sec2, 'Provide your father\'s birth name.', 'headingFatherBirthName', 'heading');
        $this->addQ($sec2, '16.a. Family Name (Last Name)', 'fatherLastName');
        $this->addQ($sec2, '16.b. Given Name (First Name)', 'fatherFirstName');
        
        $this->addQ($sec2, 'Mother\'s Name', 'headingMotherName', 'heading');
        $this->addQ($sec2, 'Provide your mother\'s birth name.', 'headingMotherBirthName', 'heading');
        $this->addQ($sec2, '17.a. Family Name (Last Name)', 'motherLastName');
        $this->addQ($sec2, '17.b. Given Name (First Name)', 'motherFirstName');

        $this->addQ($sec2, 'Your Country or Countries of Citizenship or Nationality', 'headingCountryCitizenship', 'heading');
        $this->addQ($sec2, 'List all countries where you are currently a citizen or national. If you need extra space to complete this item, use the space provided in Part 6. Additional Information.', 'headingCountryCitizenshipNote', 'heading');
        $this->addQ($sec2, '18.a. Country', 'countryCitizenship1');
        $this->addQ($sec2, '18.b. Country', 'countryCitizenship2');

        $this->addQ($sec2, 'Place of Birth', 'headingPlaceOfBirth', 'heading');
        $this->addQ($sec2, 'List the city/town/village, state/province, and country where you were born.', 'headingPlaceOfBirthNote', 'heading');
        $this->addQ($sec2, '19.a. City/Town/Village of Birth', 'cityOfBirth');
        $this->addQ($sec2, '19.b. State/Province of Birth', 'stateOfBirth');
        $this->addQ($sec2, '19.c. Country of Birth', 'countryOfBirth');
        $this->addQ($sec2, '20. Date of Birth (mm/dd/yyyy)', 'dateOfBirth', 'date');

        $this->addQ($sec2, 'Information About Your Last Arrival in the United States', 'headingLastArrival', 'heading');
        $this->addQ($sec2, '21.a. Form I-94 Arrival-Departure Record Number (if any)', 'i94Number');
        $this->addQ($sec2, '21.b. Passport Number of Your Most Recently Issued Passport', 'passportNumber');
        $this->addQ($sec2, '21.c. Travel Document Number (if any)', 'travelDocumentNumber');
        $this->addQ($sec2, '21.d. Country That Issued Your Passport or Travel Document', 'passportCountry');
        $this->addQ($sec2, '21.e. Expiration Date for Passport or Travel Document (mm/dd/yyyy)', 'passportExpirationDate', 'date');
        $this->addQ($sec2, '22. Date of Your Last Arrival Into the United States, On or About (mm/dd/yyyy)', 'dateOfLastArrival', 'date');
        $this->addQ($sec2, '23. Place of Your Last Arrival Into the United States', 'placeOfLastArrival');
        $this->addQ($sec2, '24. Immigration Status at Your Last Arrival (for example, B-2 visitor, F-1 student, or no status)', 'statusAtLastArrival');
        $this->addQ($sec2, '25. Your Current Immigration Status or Category (for example, B-2 visitor, F-1 student, parolee, deferred action, or no status or category)', 'currentImmigrationStatus');
        $this->addQ($sec2, '26. Student and Exchange Visitor Information System (SEVIS) Number (if any)', 'sevisNumber');

        $this->addQ($sec2, 'Information About Your Eligibility Category', 'headingEligibilityCategory', 'heading');
        $this->addQ($sec2, '27. Eligibility Category. Refer to the Who May File Form I-765 section of the Form I-765 Instructions to determine the appropriate eligibility category for this application. Enter the appropriate letter and number for your eligibility category below (for example, (a)(8), (c)(17)(iii)).', 'eligibilityCategory');
        
        $this->addQ($sec2, '28. (c)(3)(C) STEM OPT Eligibility Category. If you entered the eligibility category (c)(3)(C) in Item Number 27., provide the information requested in Item Numbers 28.a - 28.c.', 'headingStemOpt', 'heading');
        $this->addQ($sec2, '28.a. Degree', 'stemDegree');
        $this->addQ($sec2, '28.b. Employer\'s Name as Listed in E-Verify', 'stemEmployerEverifyName');
        $this->addQ($sec2, '28.c. Employer\'s E-Verify Company Identification Number or a Valid E-Verify Client Company Identification Number', 'stemEmployerEverifyNumber');

        $this->addQ($sec2, '29. (c)(26) Eligibility Category. If you entered the eligibility category (c)(26) in Item Number 27., provide the receipt number of your H-1B spouse\'s most recent Form I-797 Notice for Form I-129, Petition for a Nonimmigrant Worker.', 'c26ReceiptNumber');

        $this->addQ($sec2, '30. (c)(8) Eligibility Category. If you entered the eligibility category (c)(8) in Item Number 27., have you EVER been arrested for and/or convicted of any crime?', 'c8Arrested', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: If you answered "Yes" to Item Number 30., refer to Special Filing Instructions for Those With Pending Asylum Applications (c)(8) in the Required Documentation section of the Form I-765 Instructions for information about providing court dispositions.', 'headingC8Note', 'heading');

        $this->addQ($sec2, '31.a. (c)(35) and (c)(36) Eligibility Category. If you entered the eligibility category (c)(35) in Item Number 27., please provide the receipt number of your Form I-797 Notice for Form I-140, Immigrant Petition for Alien Worker. If you entered the eligibility category (c)(36) in Item Number 27., please provide the receipt number of your spouse\'s or parent\'s Form I-797 Notice for Form I-140.', 'c35c36ReceiptNumber');
        $this->addQ($sec2, '31.b. If you entered the eligibility category (c)(35) or (c)(36) in Item Number 27., have you EVER been arrested for and/or convicted of any crime?', 'c35c36Arrested', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: If you answered "Yes" to Item Number 31.b., refer to Employment-Based Nonimmigrant Categories, Items 8. - 9., in the Who May File Form I-765 section of the Form I-765 Instructions for information about providing court dispositions.', 'headingC35C36Note', 'heading');

        // Part 3. Applicant's Statement, Contact Information, Declaration, Certification, and Signature
        $sec3 = $form->sections()->create(['title' => 'Part 3. Applicant\'s Statement, Contact Information, Declaration, Certification, and Signature', 'order' => 3]);
        $this->addQ($sec3, 'Applicant\'s Statement', 'headingApplicantStatement', 'heading');
        $this->addQ($sec3, 'NOTE: Read the Penalties section of the Form I-765 Instructions before completing this section. You must file Form I-765 while in the United States. Select the box for either Item Number 1.a. or 1.b. If applicable, select the box for Item Number 2.', 'headingApplicantStatementNote', 'heading');
        $this->addQ($sec3, '1.a. I can read and understand English, and I have read and understand every question and instruction on this application and my answer to every question.', 'statementEnglish', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, '1.b. The interpreter named in Part 4. read to me every question and instruction on this application and my answer to every question in:', 'statementInterpreterLanguage');
        $this->addQ($sec3, '2. At my request, the preparer named in Part 5., prepared this application for me based only upon information I provided or authorized.', 'statementPreparer', 'radio', ['Yes', 'No']);

        $this->addQ($sec3, 'Applicant\'s Contact Information', 'headingApplicantContact', 'heading');
        $this->addQ($sec3, '3. Applicant\'s Daytime Telephone Number', 'applicantDaytimePhone');
        $this->addQ($sec3, '4. Applicant\'s Mobile Telephone Number (if any)', 'applicantMobilePhone');
        $this->addQ($sec3, '5. Applicant\'s Email Address (if any)', 'applicantEmailAddress');
        $this->addQ($sec3, '6. Select this box if you are a Salvadoran or Guatemalan national eligible for benefits under the ABC settlement agreement.', 'abcSettlement', 'checkbox', ['Yes']);

        $this->addQ($sec3, 'Applicant\'s Declaration and Certification', 'headingApplicantCertification', 'heading');
        $this->addQ($sec3, 'I certify, under penalty of perjury, that all of the information in my application and any document submitted with it were provided or authorized by me...', 'headingApplicantCertText', 'heading');
        
        $this->addQ($sec3, 'Applicant\'s Signature', 'headingApplicantSignature', 'heading');
        $this->addQ($sec3, '7.a. Applicant\'s Signature', 'applicantSignature');
        $this->addQ($sec3, '7.b. Date of Signature (mm/dd/yyyy)', 'applicantSignatureDate', 'date');


        // Part 4. Interpreter's Contact Information, Certification, and Signature
        $sec4 = $form->sections()->create(['title' => 'Part 4. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 4]);
        $this->addQ($sec4, 'Provide the following information about the interpreter.', 'headingInterpreterInfo', 'heading');
        $this->addQ($sec4, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec4, '1.a. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec4, '1.b. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec4, '2. Interpreter\'s Business or Organization Name (if any)', 'interpreterBusiness');

        $this->addQ($sec4, 'Interpreter\'s Mailing Address', 'headingInterpreterMailing', 'heading');
        $this->addQ($sec4, '3.a. Street Number and Name', 'interpreterMailingStreet');
        $this->addQ($sec4, '3.b. Apt. Ste. Flr.', 'interpreterMailingAptSteFlr');
        $this->addQ($sec4, '3.c. City or Town', 'interpreterMailingCity');
        $this->addQ($sec4, '3.d. State', 'interpreterMailingState');
        $this->addQ($sec4, '3.e. ZIP Code', 'interpreterMailingZip');
        $this->addQ($sec4, '3.f. Province', 'interpreterMailingProvince');
        $this->addQ($sec4, '3.g. Postal Code', 'interpreterMailingPostalCode');
        $this->addQ($sec4, '3.h. Country', 'interpreterMailingCountry');

        $this->addQ($sec4, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec4, '4. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec4, '5. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobilePhone');
        $this->addQ($sec4, '6. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');

        $this->addQ($sec4, 'Interpreter\'s Certification', 'headingInterpreterCertification', 'heading');
        $this->addQ($sec4, 'I certify, under penalty of perjury, that I am fluent in English and:', 'interpreterLanguage');

        $this->addQ($sec4, 'Interpreter\'s Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec4, '7.a. Interpreter\'s Signature', 'interpreterSignature');
        $this->addQ($sec4, '7.b. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');


        // Part 5. Contact Information, Declaration, and Signature of the Person Preparing this Application
        $sec5 = $form->sections()->create(['title' => 'Part 5. Contact Information, Declaration, and Signature of the Person Preparing this Application', 'order' => 5]);
        $this->addQ($sec5, 'Provide the following information about the preparer.', 'headingPreparerInfo', 'heading');
        $this->addQ($sec5, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec5, '1.a. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec5, '1.b. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec5, '2. Preparer\'s Business or Organization Name (if any)', 'preparerBusiness');

        $this->addQ($sec5, 'Preparer\'s Mailing Address', 'headingPreparerMailing', 'heading');
        $this->addQ($sec5, '3.a. Street Number and Name', 'preparerMailingStreet');
        $this->addQ($sec5, '3.b. Apt. Ste. Flr.', 'preparerMailingAptSteFlr');
        $this->addQ($sec5, '3.c. City or Town', 'preparerMailingCity');
        $this->addQ($sec5, '3.d. State', 'preparerMailingState');
        $this->addQ($sec5, '3.e. ZIP Code', 'preparerMailingZip');
        $this->addQ($sec5, '3.f. Province', 'preparerMailingProvince');
        $this->addQ($sec5, '3.g. Postal Code', 'preparerMailingPostalCode');
        $this->addQ($sec5, '3.h. Country', 'preparerMailingCountry');

        $this->addQ($sec5, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec5, '4. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec5, '5. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobilePhone');
        $this->addQ($sec5, '6. Preparer\'s Email Address (if any)', 'preparerEmailAddress');

        $this->addQ($sec5, 'Preparer\'s Statement', 'headingPreparerStatement', 'heading');
        $this->addQ($sec5, '7.a. I am not an attorney or accredited representative but have prepared this application on behalf of the applicant and with the applicant\'s consent.', 'preparerStatementNotAttorney', 'radio', ['Yes', 'No']);
        $this->addQ($sec5, '7.b. I am an attorney or accredited representative and my representation of the applicant in this case', 'preparerStatementAttorney', 'radio', ['extends', 'does not extend']);

        $this->addQ($sec5, 'Preparer\'s Certification', 'headingPreparerCertification', 'heading');
        $this->addQ($sec5, 'By my signature, I certify, under penalty of perjury, that I prepared this application at the request of the applicant...', 'headingPreparerCertText', 'heading');

        $this->addQ($sec5, 'Preparer\'s Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec5, '8.a. Preparer\'s Signature', 'preparerSignature');
        $this->addQ($sec5, '8.b. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');


        // Part 6. Additional Information
        $sec6 = $form->sections()->create(['title' => 'Part 6. Additional Information', 'order' => 6]);
        $this->addQ($sec6, '1.a Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec6, '1.b. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec6, '1.c. Middle Name', 'additionalMiddleName');
        $this->addQ($sec6, '2. A-Number (if any)', 'additionalANumber');
        
        for ($i = 3; $i <= 7; $i++) {
            $this->addQ($sec6, "{$i}.a. Page Number", "additional{$i}Page");
            $this->addQ($sec6, "{$i}.b. Part Number", "additional{$i}Part");
            $this->addQ($sec6, "{$i}.c. Item Number", "additional{$i}Item");
            $this->addQ($sec6, "{$i}.d. Additional Information", "additional{$i}Info", 'textarea');
        }

        echo "Successfully seeded I-765 form!\n";
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