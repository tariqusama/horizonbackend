<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI821DSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-821D%')->orWhere('subtitle', 'like', '%I-821D%')->first();
        if (!$service) {
            echo "Service I-821D not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-821d'],
            ['name' => 'Consideration of Deferred Action for Childhood Arrivals', 'description' => 'Form I-821D']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Information About You (For Initial and Renewal Requests)
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About You (For Initial and Renewal Requests)', 'order' => 1]);
        $this->addQ($sec1, '1. I am requesting:', 'requestType', 'radio', [
            'Initial Request - Consideration of Deferred Action for Childhood Arrivals',
            'Renewal Request - Consideration of Deferred Action for Childhood Arrivals'
        ]);
        $this->addQ($sec1, '2. For this Renewal request, my most recent period of Deferred Action for Childhood Arrivals expires on (mm/dd/yyyy)', 'renewalExpiresOn', 'date');
        
        $this->addQ($sec1, 'Full Legal Name', 'headingFullName', 'heading');
        $this->addQ($sec1, '3.a. Family Name (Last Name)', 'lastName');
        $this->addQ($sec1, '3.b. Given Name (First Name)', 'firstName');
        $this->addQ($sec1, '3.c. Middle Name', 'middleName');

        $this->addQ($sec1, 'U.S. Mailing Address', 'headingMailingAddress', 'heading');
        $this->addQ($sec1, '4.a. In Care Of Name (if applicable)', 'mailingInCareOf');
        $this->addQ($sec1, '4.b. Street Number and Name', 'mailingStreet');
        $this->addQ($sec1, '4.c. Apt. Ste. Flr.', 'mailingAptSteFlr');
        $this->addQ($sec1, '4.d. City or Town', 'mailingCity');
        $this->addQ($sec1, '4.e. State', 'mailingState');
        $this->addQ($sec1, '4.f. ZIP Code', 'mailingZip');

        $this->addQ($sec1, 'Removal Proceedings Information', 'headingRemovalProceedings', 'heading');
        $this->addQ($sec1, '5. Are you NOW or have you EVER been in removal proceedings, or do you have a removal order issued in any other context?', 'inRemovalProceedings', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec1, 'Status or outcome of removal proceedings:', 'headingRemovalStatus', 'heading');
        $this->addQ($sec1, '6.a. Currently in Proceedings (Active)', 'removalActive', 'checkbox');
        $this->addQ($sec1, '6.b. Currently in Proceedings (Administratively Closed)', 'removalAdminClosed', 'checkbox');
        $this->addQ($sec1, '6.c. Terminated', 'removalTerminated', 'checkbox');
        $this->addQ($sec1, '6.d. Subject to a Final Order', 'removalFinalOrder', 'checkbox');
        $this->addQ($sec1, '6.e. Other (Explain in Part 8)', 'removalOther', 'checkbox');
        $this->addQ($sec1, '6.f. Most Recent Date of Proceedings (mm/dd/yyyy)', 'removalRecentDate', 'date');
        $this->addQ($sec1, '6.g. Location of Proceedings', 'removalLocation');

        $this->addQ($sec1, 'Other Information', 'headingOtherInformation', 'heading');
        $this->addQ($sec1, '7. Alien Registration Number (A-Number) (if any)', 'aNumber');
        $this->addQ($sec1, '8. U.S. Social Security Number (if any)', 'ssn');
        $this->addQ($sec1, '9. Date of Birth (mm/dd/yyyy)', 'dob', 'date');
        $this->addQ($sec1, '10. Sex', 'sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec1, '11.a. City/Town/Village of Birth', 'cityOfBirth');
        $this->addQ($sec1, '11.b. Country of Birth', 'countryOfBirth');
        $this->addQ($sec1, '12. Current Country of Residence', 'countryOfResidence');
        $this->addQ($sec1, '13. Country of Citizenship or Nationality', 'countryOfCitizenship');
        $this->addQ($sec1, '14. Marital Status', 'maritalStatus', 'radio', ['Married', 'Widowed', 'Single', 'Divorced']);

        $this->addQ($sec1, 'Other Names Used (If Applicable)', 'headingOtherNames', 'heading');
        $this->addQ($sec1, '15.a. Family Name (Last Name)', 'otherNameFamily');
        $this->addQ($sec1, '15.b. Given Name (First Name)', 'otherNameGiven');
        $this->addQ($sec1, '15.c. Middle Name', 'otherNameMiddle');

        $this->addQ($sec1, 'Processing Information', 'headingProcessing', 'heading');
        $this->addQ($sec1, '16. Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec1, '17. Race (Select all applicable boxes)', 'race', 'checkbox', ['White', 'Asian', 'Black or African American', 'American Indian or Alaska Native', 'Native Hawaiian or Other Pacific Islander']);
        $this->addQ($sec1, '18. Height: Feet', 'heightFeet', 'number');
        $this->addQ($sec1, '18. Height: Inches', 'heightInches', 'number');
        $this->addQ($sec1, '19. Weight: Pounds', 'weightPounds', 'number');
        $this->addQ($sec1, '20. Eye Color', 'eyeColor', 'radio', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec1, '21. Hair Color', 'hairColor', 'radio', ['Bald (No hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);

        // Part 2. Residence and Travel Information
        $sec2 = $form->sections()->create(['title' => 'Part 2. Residence and Travel Information', 'order' => 2]);
        $this->addQ($sec2, '1. I have been continuously residing in the U.S. since at least June 15, 2007, up to the present time.', 'residingSince2007', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec2, 'Present Address', 'headingPresentAddress', 'heading');
        $this->addQ($sec2, '2.a. Street Number and Name', 'presentAddrStreet');
        $this->addQ($sec2, '2.b. Apt. Ste. Flr.', 'presentAddrAptSteFlr');
        $this->addQ($sec2, '2.c. City or Town', 'presentAddrCity');
        $this->addQ($sec2, '2.d. State', 'presentAddrState');
        $this->addQ($sec2, '2.e. ZIP Code', 'presentAddrZip');
        $this->addQ($sec2, '2.f. Dates at this residence From (mm/dd/yyyy)', 'presentAddrFrom', 'date');

        for ($i = 3; $i <= 5; $i++) {
            $addrNum = $i - 2;
            $this->addQ($sec2, "Address {$addrNum}", "headingAddress{$addrNum}", 'heading');
            $this->addQ($sec2, "{$i}.a. Street Number and Name", "addr{$addrNum}Street");
            $this->addQ($sec2, "{$i}.b. Apt. Ste. Flr.", "addr{$addrNum}AptSteFlr");
            $this->addQ($sec2, "{$i}.c. City or Town", "addr{$addrNum}City");
            $this->addQ($sec2, "{$i}.d. State", "addr{$addrNum}State");
            $this->addQ($sec2, "{$i}.e. ZIP Code", "addr{$addrNum}Zip");
            $this->addQ($sec2, "{$i}.f. Dates From", "addr{$addrNum}From", 'date');
            $this->addQ($sec2, "{$i}.f. Dates To", "addr{$addrNum}To", 'date');
        }

        $this->addQ($sec2, 'Travel Information', 'headingTravelInfo', 'heading');
        $this->addQ($sec2, 'Departure 1', 'headingDeparture1', 'heading');
        $this->addQ($sec2, '6.a. Departure Date (mm/dd/yyyy)', 'departure1Date', 'date');
        $this->addQ($sec2, '6.b. Return Date (mm/dd/yyyy)', 'departure1ReturnDate', 'date');
        $this->addQ($sec2, '6.c. Reason for Departure', 'departure1Reason');

        $this->addQ($sec2, 'Departure 2', 'headingDeparture2', 'heading');
        $this->addQ($sec2, '7.a. Departure Date (mm/dd/yyyy)', 'departure2Date', 'date');
        $this->addQ($sec2, '7.b. Return Date (mm/dd/yyyy)', 'departure2ReturnDate', 'date');
        $this->addQ($sec2, '7.c. Reason for Departure', 'departure2Reason');

        $this->addQ($sec2, '8. Have you left the United States without advance parole on or after August 15, 2012?', 'leftWithoutParole', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, '9.a. What country issued your last passport?', 'lastPassportCountry');
        $this->addQ($sec2, '9.b. Passport Number', 'lastPassportNumber');
        $this->addQ($sec2, '9.c. Passport Expiration Date (mm/dd/yyyy)', 'lastPassportExpiration', 'date');
        $this->addQ($sec2, '10. Border Crossing Card Number (if any)', 'borderCrossingCardNumber');

        // Part 3. For Initial Requests Only
        $sec3 = $form->sections()->create(['title' => 'Part 3. For Initial Requests Only', 'order' => 3]);
        $this->addQ($sec3, '1. I initially arrived and established residence in the U.S. prior to 16 years of age.', 'arrivedPrior16', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, '2. Date of Initial Entry into the United States (on or about)', 'initialEntryDate', 'date');
        $this->addQ($sec3, '3. Place of Initial Entry into the United States', 'initialEntryPlace');
        $this->addQ($sec3, '4. Immigration Status on June 15, 2012 (e.g., No Lawful Status, Status Expired, Parole Expired)', 'statusJune15');
        
        $this->addQ($sec3, '5.a. Were you EVER issued an Arrival-Departure Record (Form I-94, I-94W, or I-95)?', 'issuedI94', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, '5.b. Form I-94, I-94W, or I-95 number (if available)', 'i94Number');
        $this->addQ($sec3, '5.c. Date your authorized stay expired', 'i94Expiration', 'date');

        $this->addQ($sec3, 'Education Information', 'headingEducation', 'heading');
        $this->addQ($sec3, '6. Indicate how you meet the education guideline (e.g., Graduated from high school, Received a GED, Currently in school)', 'educationGuideline');
        $this->addQ($sec3, '7. Name, City, and State of School Currently Attending or Where Education Received', 'schoolInfo');
        $this->addQ($sec3, '8. Date of Graduation or date of last attendance', 'graduationDate', 'date');

        $this->addQ($sec3, 'Military Service Information', 'headingMilitary', 'heading');
        $this->addQ($sec3, '9. Were you a member of the U.S. Armed Forces or U.S. Coast Guard?', 'memberMilitary', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, '9.a. Military Branch', 'militaryBranch');
        $this->addQ($sec3, '9.b. Service Start Date (mm/dd/yyyy)', 'militaryStartDate', 'date');
        $this->addQ($sec3, '9.c. Discharge Date (mm/dd/yyyy)', 'militaryDischargeDate', 'date');
        $this->addQ($sec3, '9.d. Type of Discharge', 'militaryDischargeType');

        // Part 4. Criminal, National Security, and Public Safety Information
        $sec4 = $form->sections()->create(['title' => 'Part 4. Criminal, National Security, and Public Safety Information', 'order' => 4]);
        $this->addQ($sec4, '1. Have you EVER been arrested for, charged with, or convicted of a felony or misdemeanor, including incidents handled in juvenile court, in the United States?', 'arrestedUS', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '2. Have you EVER been arrested for, charged with, or convicted of a crime in any country other than the United States?', 'arrestedForeign', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '3. Have you EVER engaged in, do you continue to engage in, or plan to engage in terrorist activities?', 'terroristActivities', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '4. Are you NOW or have you EVER been a member of a gang?', 'gangMember', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, 'Have you EVER engaged in, ordered, incited, assisted, or otherwise participated in any of the following:', 'headingCrimes', 'heading');
        $this->addQ($sec4, '5.a. Acts involving torture, genocide, or human trafficking?', 'tortureGenocide', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '5.b. Killing any person?', 'killingPerson', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '5.c. Severely injuring any person?', 'injuringPerson', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '5.d. Any kind of sexual contact or relations with any person who was being forced or threatened?', 'sexualRelations', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '6. Have you EVER recruited, enlisted, conscripted, or used any person to serve in or help an armed force or group while such person was under age 15?', 'useChildSoldiers', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '7. Have you EVER used any person under age 15 to take part in hostilities, or to help or provide services to people in combat?', 'useChildHostilities', 'radio', ['Yes', 'No']);

        // Part 5. Statement, Certification, Signature, and Contact Information of the Requestor
        $sec5 = $form->sections()->create(['title' => 'Part 5. Statement, Certification, Signature, and Contact Information of the Requestor', 'order' => 5]);
        $this->addQ($sec5, '1.a. I can read and understand English, and have read and understand each and every question...', 'statementEnglish', 'radio', ['Yes', 'No']);
        $this->addQ($sec5, '1.b. The interpreter named in Part 6. has read to me each and every question... in language:', 'statementInterpreterLanguage');
        $this->addQ($sec5, '2.a. Requestor\'s Signature', 'requestorSignature');
        $this->addQ($sec5, '2.b. Date of Signature (mm/dd/yyyy)', 'requestorSignatureDate', 'date');
        $this->addQ($sec5, '3. Requestor\'s Daytime Telephone Number', 'requestorDaytimePhone');
        $this->addQ($sec5, '4. Requestor\'s Mobile Telephone Number', 'requestorMobilePhone');
        $this->addQ($sec5, '5. Requestor\'s Email Address', 'requestorEmailAddress');

        // Part 6. Contact Information, Certification, and Signature of the Interpreter
        $sec6 = $form->sections()->create(['title' => 'Part 6. Contact Information, Certification, and Signature of the Interpreter', 'order' => 6]);
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
        $this->addQ($sec6, '5. Interpreter\'s Email Address', 'interpreterEmailAddress');

        $this->addQ($sec6, 'Interpreter\'s Certification and Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec6, 'I certify that I am fluent in English and:', 'interpreterLanguage');
        $this->addQ($sec6, '6.a. Interpreter\'s Signature', 'interpreterSignature');
        $this->addQ($sec6, '6.b. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');

        // Part 7. Contact Information, Declaration, and Signature of the Person Preparing this Request
        $sec7 = $form->sections()->create(['title' => 'Part 7. Contact Information, Declaration, and Signature of the Person Preparing this Request', 'order' => 7]);
        $this->addQ($sec7, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec7, '1.a. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec7, '1.b. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec7, '2. Preparer\'s Business or Organization Name', 'preparerBusiness');

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
        $this->addQ($sec7, '5. Preparer\'s Fax Number', 'preparerFaxPhone');
        $this->addQ($sec7, '6. Preparer\'s Email Address', 'preparerEmailAddress');

        $this->addQ($sec7, 'Preparer\'s Declaration and Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec7, '7.a. Preparer\'s Signature', 'preparerSignature');
        $this->addQ($sec7, '7.b. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');

        // Part 8. Additional Information
        $sec8 = $form->sections()->create(['title' => 'Part 8. Additional Information', 'order' => 8]);
        $this->addQ($sec8, '1.a Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec8, '1.b. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec8, '1.c. Middle Name', 'additionalMiddleName');
        $this->addQ($sec8, '2. A-Number (if any)', 'additionalANumber');
        
        for ($i = 3; $i <= 5; $i++) {
            $this->addQ($sec8, "{$i}.a. Page Number", "additional{$i}Page");
            $this->addQ($sec8, "{$i}.b. Part Number", "additional{$i}Part");
            $this->addQ($sec8, "{$i}.c. Item Number", "additional{$i}Item");
            $this->addQ($sec8, "{$i}.d. Additional Information", "additional{$i}Info", 'textarea');
        }

        echo "Successfully seeded I-821D form!\n";
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