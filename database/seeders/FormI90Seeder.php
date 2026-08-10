<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI90Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-90%')->orWhere('subtitle', 'like', '%I-90%')->first();
        if (!$service) {
            echo "Service I-90 not found. (Warning: Ensure the service exists if you want to link it)\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-90'],
            ['name' => 'Application to Replace Permanent Resident Card', 'description' => 'Form I-90']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Information About You
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About You', 'order' => 1]);
        $this->addQ($sec1, '1. Alien Registration Number (A-Number)', 'alienRegistrationNumber');
        $this->addQ($sec1, '2. USCIS Online Account Number (if any)', 'uscisAccountNumber');

        $this->addQ($sec1, 'Your Full Name', 'headingFullName', 'heading');
        $this->addQ($sec1, 'NOTE: Your card will be issued in this name.', 'headingNameNote', 'heading');
        $this->addQ($sec1, '3.a. Family Name (Last Name)', 'familyName');
        $this->addQ($sec1, '3.b. Given Name (First Name)', 'givenName');
        $this->addQ($sec1, '3.c. Middle Name', 'middleName');

        $this->addQ($sec1, '4. Has your name legally changed since the issuance of your Permanent Resident Card?', 'hasNameChanged', 'radio', [
            'Yes (Proceed to Item Numbers 5.a. - 5.c.)',
            'No (Proceed to Item Numbers 6.a. - 6.i.)',
            'N/A - I never received my previous card. (Proceed to Item Numbers 6.a. - 6.i.)'
        ]);

        $this->addQ($sec1, 'Provide your name exactly as it is printed on your current Permanent Resident Card.', 'headingPreviousName', 'heading');
        $this->addQ($sec1, 'NOTE: Attach all evidence of your legal name change with this application.', 'headingPreviousNameNote', 'heading');
        $this->addQ($sec1, '5.a. Family Name (Last Name)', 'prevFamilyName');
        $this->addQ($sec1, '5.b. Given Name (First Name)', 'prevGivenName');
        $this->addQ($sec1, '5.c. Middle Name', 'prevMiddleName');

        $this->addQ($sec1, 'Mailing Address', 'headingMailingAddress', 'heading');
        $this->addQ($sec1, '6.a. In Care Of Name', 'mailingInCareOf');
        $this->addQ($sec1, '6.b. Street Number and Name', 'mailingStreet');
        $this->addQ($sec1, '6.c. Apt. Ste. Flr.', 'mailingAptSteFlr');
        $this->addQ($sec1, '6.d. City or Town', 'mailingCity');
        $this->addQ($sec1, '6.e. State', 'mailingState');
        $this->addQ($sec1, '6.f. ZIP Code', 'mailingZip');
        $this->addQ($sec1, '6.g. Province', 'mailingProvince');
        $this->addQ($sec1, '6.h. Postal Code', 'mailingPostalCode');
        $this->addQ($sec1, '6.i. Country', 'mailingCountry');

        $this->addQ($sec1, 'Physical Address', 'headingPhysicalAddress', 'heading');
        $this->addQ($sec1, 'Provide this information only if different than mailing address.', 'headingPhysicalAddressNote', 'heading');
        $this->addQ($sec1, '7.a. Street Number and Name', 'physicalStreet');
        $this->addQ($sec1, '7.b. Apt. Ste. Flr.', 'physicalAptSteFlr');
        $this->addQ($sec1, '7.c. City or Town', 'physicalCity');
        $this->addQ($sec1, '7.d. State', 'physicalState');
        $this->addQ($sec1, '7.e. ZIP Code', 'physicalZip');
        $this->addQ($sec1, '7.f. Province', 'physicalProvince');
        $this->addQ($sec1, '7.g. Postal Code', 'physicalPostalCode');
        $this->addQ($sec1, '7.h. Country', 'physicalCountry');

        $this->addQ($sec1, '8. Date of Birth (mm/dd/yyyy)', 'dateOfBirth', 'date');
        $this->addQ($sec1, '9. City/Town/Village of Birth', 'cityOfBirth');
        $this->addQ($sec1, '10. Country of Birth', 'countryOfBirth');
        
        $this->addQ($sec1, '11. Mother\'s Name: Given Name (First Name)', 'motherFirstName');
        $this->addQ($sec1, '12. Father\'s Name: Given Name (First Name)', 'fatherFirstName');
        $this->addQ($sec1, '13. Class of Admission', 'classOfAdmission');
        $this->addQ($sec1, '14. Date of Admission (mm/dd/yyyy)', 'dateOfAdmission', 'date');
        $this->addQ($sec1, '15. U.S. Social Security Number (if any)', 'socialSecurityNumber');
        $this->addQ($sec1, '16. Sex', 'sex', 'radio', ['Male', 'Female']);

        // Part 2. Application Type
        $sec2 = $form->sections()->create(['title' => 'Part 2. Application Type', 'order' => 2]);
        $this->addQ($sec2, 'NOTE: If your conditional permanent resident status (for example: CR1, CR2, CF1, CF2) is expiring within the next 90 days, then do not file this application. (See the What is the Purpose of This Application section of the Form I-90 Instructions for further information.)', 'headingConditionalNote', 'heading');
        $this->addQ($sec2, 'My status is (Select only one box):', 'myStatus', 'radio', [
            '1.a. Lawful Permanent Resident (Proceed to Section A.)',
            '1.b. Permanent Resident - In Commuter Status (Proceed to Section A.)',
            '1.c. Conditional Permanent Resident (Proceed to Section B.)'
        ]);

        $this->addQ($sec2, 'Reason for Application (Select only one box)', 'headingReasonForApp', 'heading');
        $this->addQ($sec2, 'NOTE: If you are filing this application before your 14th birthday, or more than 30 days after your 14th birthday, you must select reason 2.j. However, if your card has expired, you must select reason 2.f.', 'headingReasonForAppNote', 'heading');
        
        $this->addQ($sec2, 'Section A. (To be used only by a lawful permanent resident or a permanent resident in commuter status.)', 'headingSectionA', 'heading');
        $this->addQ($sec2, 'Reason for Application (Section A)', 'reasonForApplicationSectionA', 'radio', [
            '2.a. My previous card has been lost, stolen, or destroyed.',
            '2.b. My previous card was issued but never received.',
            '2.c. My existing card has been mutilated.',
            '2.d. My existing card has incorrect data because of Department of Homeland Security (DHS) error. (Attach your existing card with incorrect data along with this application.)',
            '2.e. My name or other biographic information has been legally changed since issuance of my existing card.',
            '2.f. My existing card has already expired or will expire within six months.',
            '2.g.1. I have reached my 14th birthday and am registering as required. My existing card will expire AFTER my 16th birthday.',
            '2.g.2. I have reached my 14th birthday and am registering as required. My existing card will expire BEFORE my 16th birthday.',
            '2.h.1. I am a permanent resident who is taking up commuter status.',
            '2.h.2. I am a commuter who is taking up actual residence in the United States.',
            '2.i. I have been automatically converted to lawful permanent resident status.',
            '2.j. I have a prior edition of the Alien Registration Card, or I am applying to replace my current Permanent Resident Card for a reason that is not specified above.'
        ]);
        $this->addQ($sec2, '2.h.1.a. My Port-of-Entry (POE) into the United States will be: City or Town and State', 'commuterPoe');

        $this->addQ($sec2, 'Section B. (To be used only by a conditional permanent resident.)', 'headingSectionB', 'heading');
        $this->addQ($sec2, 'Reason for Application (Section B)', 'reasonForApplicationSectionB', 'radio', [
            '3.a. My previous card has been lost, stolen, or destroyed.',
            '3.b. My previous card was issued but never received.',
            '3.c. My existing card has been mutilated.',
            '3.d. My existing card has incorrect data because of DHS error. (Attach your existing permanent resident card with incorrect data along with this application.)',
            '3.e. My name or other biographic information has legally changed since the issuance of my existing card.'
        ]);

        // Part 3. Processing Information
        $sec3 = $form->sections()->create(['title' => 'Part 3. Processing Information', 'order' => 3]);
        $this->addQ($sec3, '1. Location where you applied for an immigrant visa or adjustment of status:', 'locationAppliedVisa');
        $this->addQ($sec3, '2. Location where your immigrant visa was issued or USCIS office where you were granted adjustment of status:', 'locationIssuedVisa');
        
        $this->addQ($sec3, 'Complete Item Numbers 3.a. and 3.a1. if you entered the United States with an immigrant visa. (If you were granted adjustment of status, proceed to Item Number 4.)', 'headingImmigrantVisaInstruction', 'heading');
        $this->addQ($sec3, '3.a. Destination in the United States at time of admission', 'destinationAtAdmission');
        $this->addQ($sec3, '3.a.1. Port-of-Entry where admitted to the United States: City or Town and State', 'poeAtAdmission');

        $this->addQ($sec3, '4. Have you ever been in exclusion, deportation, or removal proceedings or ordered removed from the United States?', 'inRemovalProceedings', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, '5. Since you were granted permanent residence, have you ever filed Form I-407, Abandonment by Alien of Status as Lawful Permanent Resident, or otherwise been determined to have abandoned your status?', 'abandonedStatus', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, 'NOTE: If you answered "Yes" to Item Numbers 4. or 5. above, provide a detailed explanation in the space provided in Part 8. Additional Information.', 'headingExplanationNote', 'heading');

        $this->addQ($sec3, 'Biographic Information', 'headingBiographicInfo', 'heading');
        $this->addQ($sec3, '6. Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec3, '7. Race (Select all applicable boxes)', 'race', 'checkbox', ['American Indian or Alaska Native', 'Asian', 'Black or African American', 'Native Hawaiian or Other Pacific Islander', 'White']);
        $this->addQ($sec3, '8. Height: Feet', 'heightFeet', 'number');
        $this->addQ($sec3, '8. Height: Inches', 'heightInches', 'number');
        $this->addQ($sec3, '9. Weight: Pounds', 'weightPounds', 'number');
        $this->addQ($sec3, '10. Eye Color (Select only one box)', 'eyeColor', 'radio', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec3, '11. Hair Color (Select only one box)', 'hairColor', 'radio', ['Bald (No hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);

        // Part 4. Accommodations for Individuals with Disabilities and/or Impairments
        $sec4 = $form->sections()->create(['title' => 'Part 4. Accommodations for Individuals with Disabilities and/or Impairments', 'order' => 4]);
        $this->addQ($sec4, '1. Are you requesting an accommodation because of your disabilities and/or impairments?', 'requestAccommodation', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, 'If you answered "Yes," select any applicable boxes:', 'headingAccommodationYes', 'heading');
        $this->addQ($sec4, '1.a. I am deaf or hard of hearing and request the following accommodation (If you are requesting a sign-language interpreter, indicate for which language (for example, American Sign Language)):', 'accommodationDeaf', 'textarea');
        $this->addQ($sec4, '1.b. I am blind or have low vision and request the following accommodation:', 'accommodationBlind', 'textarea');
        $this->addQ($sec4, '1.c. I have another type of disability and/or impairment (Describe the nature of your disability and/or impairment and the accommodation you are requesting):', 'accommodationOther', 'textarea');

        // Part 5. Applicant's Statement, Contact Information, Certification, and Signature
        $sec5 = $form->sections()->create(['title' => 'Part 5. Applicant\'s Statement, Contact Information, Certification, and Signature', 'order' => 5]);
        $this->addQ($sec5, 'NOTE: Select the box for either Item Number 1.a. or 1.b. If applicable, select the box for Item Number 2.', 'headingStatementNote', 'heading');
        $this->addQ($sec5, 'Applicant\'s Statement', 'headingApplicantStatement', 'heading');
        $this->addQ($sec5, '1.a. I can read and understand English, and I have read and understand every question and instruction on this application and my answer to every question.', 'statementEnglish', 'radio', ['Yes', 'No']);
        $this->addQ($sec5, '1.b. The interpreter named in Part 6. read to me every question and instruction on this application and my answer to every question in:', 'statementInterpreterLanguage');
        $this->addQ($sec5, '2. At my request, the preparer named in Part 7., prepared this application for me based only upon information I provided or authorized.', 'statementPreparer', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec5, 'Applicant\'s Contact Information', 'headingApplicantContact', 'heading');
        $this->addQ($sec5, '3. Applicant\'s Daytime Telephone Number', 'applicantDaytimePhone');
        $this->addQ($sec5, '4. Applicant\'s Mobile Telephone Number (if any)', 'applicantMobilePhone');
        $this->addQ($sec5, '5. Applicant\'s Email Address (if any)', 'applicantEmailAddress');

        $this->addQ($sec5, 'Applicant\'s Certification', 'headingApplicantCertification', 'heading');
        $this->addQ($sec5, 'Copies of any documents I have submitted are exact photocopies of unaltered, original documents, and I understand that USCIS may require that I submit original documents to USCIS at a later date...', 'headingApplicantCertificationText', 'heading');

        $this->addQ($sec5, 'Applicant\'s Signature', 'headingApplicantSignature', 'heading');
        $this->addQ($sec5, '6.a. Applicant\'s Signature (sign in ink)', 'applicantSignature');
        $this->addQ($sec5, '6.b. Date of Signature (mm/dd/yyyy)', 'applicantSignatureDate', 'date');

        // Part 6. Interpreter's Contact Information, Certification, and Signature
        $sec6 = $form->sections()->create(['title' => 'Part 6. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 6]);
        $this->addQ($sec6, 'Provide the following information about the interpreter.', 'headingInterpreterInfo', 'heading');
        $this->addQ($sec6, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec6, '1.a. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec6, '1.b. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec6, '2. Interpreter\'s Business or Organization Name (if any)', 'interpreterBusiness');
        
        $this->addQ($sec6, 'Interpreter\'s Mailing Address', 'headingInterpreterMailing', 'heading');
        $this->addQ($sec6, '3.a. Street Number and Name', 'interpreterMailingStreet');
        $this->addQ($sec6, '3.b. Apt. Ste. Flr.', 'interpreterMailingAptSteFlr');
        $this->addQ($sec6, '3.c. City or Town', 'interpreterMailingCity');
        $this->addQ($sec6, '3.d. State', 'interpreterMailingState');
        $this->addQ($sec6, '3.e. ZIP Code', 'interpreterMailingZip');
        $this->addQ($sec6, '3.f. Province', 'interpreterMailingProvince');
        $this->addQ($sec6, '3.g. Postal Code', 'interpreterMailingPostalCode');
        $this->addQ($sec6, '3.h. Country', 'interpreterMailingCountry');

        $this->addQ($sec6, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec6, '4. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec6, '5. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobilePhone');
        $this->addQ($sec6, '6. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');

        $this->addQ($sec6, 'Interpreter\'s Certification', 'headingInterpreterCertification', 'heading');
        $this->addQ($sec6, 'I certify, under penalty of perjury, that I am fluent in English and:', 'interpreterLanguage');
        
        $this->addQ($sec6, 'Interpreter\'s Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec6, '7.a. Interpreter\'s Signature (sign in ink)', 'interpreterSignature');
        $this->addQ($sec6, '7.b. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');

        // Part 7. Preparer
        $sec7 = $form->sections()->create(['title' => 'Part 7. Contact Information, Declaration, and Signature of the Person Preparing this Application, if Other Than the Applicant', 'order' => 7]);
        $this->addQ($sec7, 'Provide the following information about the preparer.', 'headingPreparerInfo', 'heading');
        $this->addQ($sec7, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec7, '1.a. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec7, '1.b. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec7, '2. Preparer\'s Business or Organization Name (if any)', 'preparerBusiness');

        $this->addQ($sec7, 'Preparer\'s Mailing Address', 'headingPreparerMailing', 'heading');
        $this->addQ($sec7, '3.a. Street Number and Name', 'preparerMailingStreet');
        $this->addQ($sec7, '3.b. Apt. Ste. Flr.', 'preparerMailingAptSteFlr');
        $this->addQ($sec7, '3.c. City or Town', 'preparerMailingCity');
        $this->addQ($sec7, '3.d. State', 'preparerMailingState');
        $this->addQ($sec7, '3.e. ZIP Code', 'preparerMailingZip');
        $this->addQ($sec7, '3.f. Province', 'preparerMailingProvince');
        $this->addQ($sec7, '3.g. Postal Code', 'preparerMailingPostalCode');
        $this->addQ($sec7, '3.h. Country', 'preparerMailingCountry');

        $this->addQ($sec7, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec7, '4. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec7, '5. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobilePhone');
        $this->addQ($sec7, '6. Preparer\'s Email Address (if any)', 'preparerEmailAddress');

        $this->addQ($sec7, 'Preparer\'s Statement', 'headingPreparerStatement', 'heading');
        $this->addQ($sec7, '7.a. I am not an attorney or accredited representative but have prepared this application on behalf of the applicant and with the applicant\'s consent.', 'preparerStatementNotAttorney', 'radio', ['Yes', 'No']);
        $this->addQ($sec7, '7.b. I am an attorney or accredited representative and my representation of the applicant in this case', 'preparerStatementAttorney', 'radio', ['extends', 'does not extend']);
        $this->addQ($sec7, 'NOTE: If you are an attorney or accredited representative whose representation extends beyond preparation of this application, you may be obliged to submit a completed Form G-28...', 'headingPreparerNote', 'heading');

        $this->addQ($sec7, 'Preparer\'s Certification', 'headingPreparerCertification', 'heading');
        $this->addQ($sec7, 'By my signature, I certify, under penalty of perjury, that I prepared this application at the request of the applicant...', 'headingPreparerCertText', 'heading');
        
        $this->addQ($sec7, 'Preparer\'s Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec7, '8.a. Preparer\'s Signature (sign in ink)', 'preparerSignature');
        $this->addQ($sec7, '8.b. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');

        // Part 8. Additional Information
        $sec8 = $form->sections()->create(['title' => 'Part 8. Additional Information', 'order' => 8]);
        $this->addQ($sec8, 'Your Full Name', 'headingAdditionalFullName', 'heading');
        $this->addQ($sec8, '1.a. Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec8, '1.b. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec8, '1.c. Middle Name', 'additionalMiddleName');
        $this->addQ($sec8, '2. A-Number (if any)', 'additionalANumber');

        $this->addQ($sec8, '3.a. Page Number', 'additional3Page');
        $this->addQ($sec8, '3.b. Part Number', 'additional3Part');
        $this->addQ($sec8, '3.c. Item Number', 'additional3Item');
        $this->addQ($sec8, '3.d. Additional Information', 'additional3Info', 'textarea');

        $this->addQ($sec8, '4.a. Page Number', 'additional4Page');
        $this->addQ($sec8, '4.b. Part Number', 'additional4Part');
        $this->addQ($sec8, '4.c. Item Number', 'additional4Item');
        $this->addQ($sec8, '4.d. Additional Information', 'additional4Info', 'textarea');

        $this->addQ($sec8, '5.a. Page Number', 'additional5Page');
        $this->addQ($sec8, '5.b. Part Number', 'additional5Part');
        $this->addQ($sec8, '5.c. Item Number', 'additional5Item');
        $this->addQ($sec8, '5.d. Additional Information', 'additional5Info', 'textarea');

        $this->addQ($sec8, '6.a. Page Number', 'additional6Page');
        $this->addQ($sec8, '6.b. Part Number', 'additional6Part');
        $this->addQ($sec8, '6.c. Item Number', 'additional6Item');
        $this->addQ($sec8, '6.d. Additional Information', 'additional6Info', 'textarea');
        
        $this->addQ($sec8, '7.a. Page Number', 'additional7Page');
        $this->addQ($sec8, '7.b. Part Number', 'additional7Part');
        $this->addQ($sec8, '7.c. Item Number', 'additional7Item');
        $this->addQ($sec8, '7.d. Additional Information', 'additional7Info', 'textarea');

        echo "Successfully seeded I-90 form!\n";
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