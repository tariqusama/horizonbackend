<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI131Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-131%')->orWhere('subtitle', 'like', '%I-131%')->first();
        if (!$service) {
            echo "Service I-131 not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-131'],
            ['name' => 'Application for Travel Documents, Parole Documents, and Arrival/Departure Records', 'description' => 'Form I-131']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Application Type
        $sec1 = $form->sections()->create(['title' => 'Part 1. Application Type', 'order' => 1]);
        $this->addQ($sec1, 'Select the application type below.', 'headingAppType', 'heading');
        
        $this->addQ($sec1, 'Reentry Permit', 'headingReentryPermit', 'heading');
        $this->addQ($sec1, '1. I am a lawful permanent resident or conditional permanent resident of the United States, and I am applying for a reentry permit.', 'typeReentryPermit', 'checkbox', ['Yes']);

        $this->addQ($sec1, 'Refugee Travel Document', 'headingRefugeeTravel', 'heading');
        $this->addQ($sec1, '2. I now hold refugee or asylee status in the United States, and I am applying for a Refugee Travel Document.', 'typeRefugeeStatus', 'checkbox', ['Yes']);
        $this->addQ($sec1, '3. I am a lawful permanent resident as a direct result of refugee or asylee status, and I am applying for a Refugee Travel Document.', 'typeLprRefugeeStatus', 'checkbox', ['Yes']);

        $this->addQ($sec1, 'Travel Authorization Document (for TPS beneficiaries inside the U.S.)', 'headingTps', 'heading');
        $this->addQ($sec1, '4. I am a TPS beneficiary in the United States, and I am applying for a TPS Travel Authorization Document...', 'typeTps', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'The receipt number for my last approved Form I-821 is:', 'tpsReceiptNumber');

        $this->addQ($sec1, 'Advance Parole Document (for aliens who are inside the United States)...', 'headingAdvanceParole', 'heading');
        $this->addQ($sec1, '5. I am located inside the United States, and I am applying for an Advance Parole Document... based on:', 'typeAdvanceParole', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'A. A pending Form I-485 receipt number:', 'apPendingI485Receipt');
        $this->addQ($sec1, 'B. A pending Form I-589 receipt number:', 'apPendingI589Receipt');
        $this->addQ($sec1, 'C. An approved Form I-918 or I-918 Supplement A receipt number:', 'apApprovedI918Receipt');
        $this->addQ($sec1, 'D. An approved V Nonimmigrant Status receipt number:', 'apApprovedVReceipt');
        $this->addQ($sec1, 'E. A pending Form I-687 receipt number:', 'apPendingI687Receipt');
        $this->addQ($sec1, 'F. An approved Form I-817 receipt number:', 'apApprovedI817Receipt');
        $this->addQ($sec1, 'G. Being a current parolee under INA section 212(d)(5), under class of admission:', 'apCurrentParoleeCoa');
        $this->addQ($sec1, 'H. Other (provide explanation):', 'apOtherExplanation');

        $this->addQ($sec1, 'Initial Parole Document (for aliens who are currently outside the United States)', 'headingInitialParole', 'heading');
        $this->addQ($sec1, '6. I am applying for a parole document... for the first time under one of the following specific programs:', 'typeInitialParole', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'A. Filipino World War II Veterans Parole (FWVP) Program, Form I-130 receipt number:', 'ipFwvpReceipt');
        $this->addQ($sec1, 'B. Immigrant Military Members and Veterans Initiative (IMMVI)', 'ipImmvi', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'C. Intergovernmental Parole Referral', 'ipIntergovParole', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'U.S. Federal Executive Branch Government Agency:', 'ipIntergovAgency');
        $this->addQ($sec1, 'U.S. Federal Government Agency Representative Official Email Address:', 'ipIntergovEmail');
        $this->addQ($sec1, 'D. Deferred Enforced Departure.', 'ipDed', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'E. Approved Form I-821D receipt number:', 'ipDacaReceipt');
        $this->addQ($sec1, 'F. Approved Form I-914 or I-914A receipt number:', 'ipTStatusReceipt');
        $this->addQ($sec1, 'G. Pending initial Form I-821 receipt number:', 'ipPendingTpsReceipt');
        $this->addQ($sec1, 'H. CNMI long-term residence receipt number:', 'ipCnmiReceipt');
        $this->addQ($sec1, 'I. Family Reunification Task Force (FRTF) Process Task Force Registration Number:', 'ipFrtfNumber');
        $this->addQ($sec1, 'J. Other (List specific parole program or process):', 'ipOtherProgram');
        $this->addQ($sec1, '7. I am applying... but not under a specific parole program or process.', 'typeInitialParoleNotSpecific', 'checkbox', ['Yes']);

        $this->addQ($sec1, 'Initial Request for Arrival/Departure Record for Parole In Place', 'headingPip', 'heading');
        $this->addQ($sec1, '8. I am applying for an initial period of parole in place... under:', 'typePip', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'A. Military Parole in Place (PIP)...', 'pipMilitary', 'radio', [
            'A current or former service member.',
            'A spouse, parent, son, or daughter of a current or former service member.'
        ]);
        $this->addQ($sec1, 'B. Family Reunification Task Force (FRTF) Process Task Force Registration Number:', 'pipFrtfNumber');
        $this->addQ($sec1, 'C. Other (List specific program or process):', 'pipOtherProgram');
        $this->addQ($sec1, '9. I am applying for an initial period of parole in place... but not under a specific program or process.', 'typePipNotSpecific', 'checkbox', ['Yes']);

        $this->addQ($sec1, 'Arrival/Departure Records for Re-parole', 'headingReparole', 'heading');
        $this->addQ($sec1, '10. I was initially paroled... and I am requesting a new period of parole under one of the following programs:', 'typeReparole', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'Program Type (Select one):', 'reparoleProgram', 'radio', [
            'A. Family Reunification Parole Process',
            'B. Certain Afghans Paroled Into the United States After July 31, 2021',
            'C. Re-parole Process for certain Ukrainian Citizens and Their Immediate Family Members',
            'D. Filipino World War II Veterans Parole (FWVP) Program',
            'E. Immigrant Military Members and Veterans Initiative (IMMVI)',
            'F. Central American Minors (CAM) Program',
            'G. Family Reunification Task Force (FRTF) Process',
            'H. Military Parole in Place (Military PIP)',
            'I. Other Program or Process'
        ]);
        $this->addQ($sec1, 'If IMMVI or Military PIP, select:', 'reparoleMilitaryDetails', 'radio', [
            'A current or former service member.',
            'A current spouse, child, or unmarried son or daughter of a current or former service member.',
            'Current legal guardian or surrogate of a current or former service member.',
            'A spouse, parent, son, or daughter of a current or former service member.'
        ]);
        $this->addQ($sec1, 'If Other Program or Process, list:', 'reparoleOtherText');
        $this->addQ($sec1, '11. I am requesting a new period of parole... but not under a specific program or process.', 'typeReparoleNotSpecific', 'checkbox', ['Yes']);
        $this->addQ($sec1, '12. If you selected one of the boxes in Item Numbers 10. or 11., list the Admit Until Date/Parole shown on Form I-94: (mm/dd/yyyy)', 'reparoleAdmitUntilDate', 'date');

        $this->addQ($sec1, 'Refugee Status', 'headingRefugeeStatus', 'heading');
        $this->addQ($sec1, '13. Do you hold status as a refugee, were you paroled as a refugee, or are you a lawful permanent resident as a direct result of being a refugee?', 'holdsRefugeeStatus', 'radio', ['Yes', 'No']);

        // Part 2. Information About You
        $sec2 = $form->sections()->create(['title' => 'Part 2. Information About You', 'order' => 2]);
        $this->addQ($sec2, '1. Your Full Name', 'headingFullName', 'heading');
        $this->addQ($sec2, 'Family Name (Last Name)', 'lastName');
        $this->addQ($sec2, 'Given Name (First Name)', 'firstName');
        $this->addQ($sec2, 'Middle Name (if applicable)', 'middleName');
        
        $this->addQ($sec2, '2. Other Names Used (if applicable)', 'headingOtherNames', 'heading');
        $this->addQ($sec2, 'Family Name (Last Name)', 'otherLastName');
        $this->addQ($sec2, 'Given Name (First Name)', 'otherFirstName');
        $this->addQ($sec2, 'Middle Name (if applicable)', 'otherMiddleName');

        $this->addQ($sec2, '3. Current Mailing Address or Safe Address (if applicable)', 'headingMailingAddr', 'heading');
        $this->addQ($sec2, 'In Care Of Name (if any)', 'mailingInCareOf');
        $this->addQ($sec2, 'Street Number and Name', 'mailingStreet');
        $this->addQ($sec2, 'Apt/Flr/Ste Number', 'mailingApt');
        $this->addQ($sec2, 'City or Town', 'mailingCity');
        $this->addQ($sec2, 'State', 'mailingState');
        $this->addQ($sec2, 'ZIP Code', 'mailingZip');
        $this->addQ($sec2, 'Province', 'mailingProvince');
        $this->addQ($sec2, 'Postal Code', 'mailingPostalCode');
        $this->addQ($sec2, 'Country', 'mailingCountry');

        $this->addQ($sec2, '4. Current Physical Address (if different from the above address)', 'headingPhysicalAddr', 'heading');
        $this->addQ($sec2, 'In Care Of Name (if any)', 'physicalInCareOf');
        $this->addQ($sec2, 'Street Number and Name', 'physicalStreet');
        $this->addQ($sec2, 'Apt/Flr/Ste Number', 'physicalApt');
        $this->addQ($sec2, 'City or Town', 'physicalCity');
        $this->addQ($sec2, 'State', 'physicalState');
        $this->addQ($sec2, 'ZIP Code', 'physicalZip');
        $this->addQ($sec2, 'Province', 'physicalProvince');
        $this->addQ($sec2, 'Postal Code', 'physicalPostalCode');
        $this->addQ($sec2, 'Country', 'physicalCountry');

        $this->addQ($sec2, 'Other Information', 'headingOtherInfo', 'heading');
        $this->addQ($sec2, '5. Alien Registration Number (A-Number) (if any)', 'aNumber');
        $this->addQ($sec2, '6. U.S. Social Security Number (if any)', 'ssn');
        $this->addQ($sec2, '7. Country of Citizenship or Nationality', 'countryOfCitizenship');
        $this->addQ($sec2, '8. Country of Birth', 'countryOfBirth');
        $this->addQ($sec2, '9. Date of Birth (mm/dd/yyyy)', 'dob', 'date');
        $this->addQ($sec2, '10. Sex', 'sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec2, '11. USCIS Online Account Number (if any)', 'uscisAccountNumber');
        $this->addQ($sec2, '12. Class of Admission (COA) (if any)', 'classOfAdmission');

        $this->addQ($sec2, 'If you are physically present in the United States, and you are seeking a TPS document, advance parole, re-parole, or parole in place, complete the following:', 'headingPhysicallyPresent', 'heading');
        $this->addQ($sec2, '13. Most Recent Form I-94 Arrival/Departure Record Number (if any)', 'i94Number');
        $this->addQ($sec2, '14. Expiration Date of Authorized Stay Shown on Form I-94 (if any) (mm/dd/yyyy)', 'i94Expiry', 'date');
        $this->addQ($sec2, '15. eMedical U.S. Parolee ID (USPID) (if any)', 'uspid');

        $this->addQ($sec2, 'Information About Them (Complete this section only if you are applying on behalf of someone else.)', 'headingAboutThem', 'heading');
        $this->addQ($sec2, '16. Their Full Name: Family Name (Last Name)', 'theirLastName');
        $this->addQ($sec2, 'Their Full Name: Given Name (First Name)', 'theirFirstName');
        $this->addQ($sec2, 'Their Full Name: Middle Name (if applicable)', 'theirMiddleName');
        $this->addQ($sec2, '17. Their Other Names Used: Family/Given/Middle', 'theirOtherNames');
        $this->addQ($sec2, '18. Date of Birth (mm/dd/yyyy)', 'theirDob', 'date');
        $this->addQ($sec2, '19. Country of Birth', 'theirCountryOfBirth');
        $this->addQ($sec2, '20. Country of Citizenship or Nationality', 'theirCountryOfCitizenship');
        $this->addQ($sec2, '21. Daytime Phone Number', 'theirPhone');
        $this->addQ($sec2, '22. Email Address (if any)', 'theirEmail');
        $this->addQ($sec2, '23. Alien Registration Number (A-Number) (if any)', 'theirANumber');
        $this->addQ($sec2, '24. Their Current Mailing Address', 'theirMailingAddress', 'textarea');
        $this->addQ($sec2, '25. Their Current Physical Address', 'theirPhysicalAddress', 'textarea');
        $this->addQ($sec2, '26. Class of Admission (COA) (if any)', 'theirCoa');
        $this->addQ($sec2, '27. Most Recent Form I-94 Arrival/Departure Record Number (if any)', 'theirI94Number');

        // Part 3. Biographic Information
        $sec3 = $form->sections()->create(['title' => 'Part 3. Biographic Information', 'order' => 3]);
        $this->addQ($sec3, 'Race (Select all applicable boxes)', 'race', 'checkbox', [
            'White', 'Asian', 'Black or African American', 'American Indian or Alaska Native', 'Native Hawaiian or Other Pacific Islander'
        ]);
        $this->addQ($sec3, 'Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec3, 'Height (Feet and Inches)', 'height');
        $this->addQ($sec3, 'Weight (Pounds)', 'weight');
        $this->addQ($sec3, 'Eye Color (Select only one box)', 'eyeColor', 'select', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec3, 'Hair Color (Select only one box)', 'hairColor', 'select', ['Bald (No Hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);

        // Part 4. Processing Information
        $sec4 = $form->sections()->create(['title' => 'Part 4. Processing Information', 'order' => 4]);
        $this->addQ($sec4, '1. Has the person who will receive the travel document... been in any exclusion, deportation, removal, or rescission proceedings?', 'inProceedings', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '2.a. Have you EVER before been issued a Reentry Permit or Refugee Travel Document?', 'issuedReentryPermit', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '2.b. Date Issued (mm/dd/yyyy)', 'reentryPermitDate', 'date');
        $this->addQ($sec4, '2.c. Disposition (attached, lost, stolen, damaged/destroyed, still in my possession, etc.):', 'reentryPermitDisposition');
        
        $this->addQ($sec4, '3.a. Have you EVER been issued an Advance Parole Document?', 'issuedAdvanceParole', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '3.b. Date Issued (mm/dd/yyyy)', 'advanceParoleDate', 'date');
        $this->addQ($sec4, '3.c. Disposition (attached, lost, stolen, damaged/destroyed, still in my possession, etc.):', 'advanceParoleDisposition');
        
        $this->addQ($sec4, '4. Are you requesting a replacement Reentry Permit, Refugee Travel Document, Advance Parole Document, or TPS Travel Authorization Document?', 'requestingReplacement', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '5. If you answered "Yes," select one of the following boxes:', 'replacementReason', 'radio', [
            'I received my document, but then it was lost, stolen, or damaged.',
            'My document was issued, but I did not receive it.',
            'I received my document, but it has incorrect information because of an error caused by me or because my information has changed.',
            'I received my document, but it has incorrect information because of an error not caused by me (such as a USCIS error).'
        ]);
        $this->addQ($sec4, '6.a. If replacing because of incorrect information, select the applicable box(es):', 'incorrectInfoBoxes', 'checkbox', [
            'Name', 'Date of Birth', 'A-Number', 'Sex', 'Country of Birth/Citizenship', 'Validity Date', 'Terms and Conditions', 'Photo'
        ]);
        $this->addQ($sec4, 'Provide an explanation of what is incorrect on your current document:', 'incorrectInfoExplanation', 'textarea');
        $this->addQ($sec4, '6.b. Provide the receipt number for the Form I-131 related to the document you are replacing:', 'replacementReceiptNumber');
        
        $this->addQ($sec4, 'Where do you want your Reentry Permit or Refugee Travel Document sent?', 'whereToSendDocument', 'radio', [
            '7.a. To the U.S. address shown in Part 2.',
            '7.b. To a U.S. Embassy, U.S. Consulate, USCIS international field office, or DHS office overseas'
        ]);
        $this->addQ($sec4, 'If 7.b., specify City or Town and Country:', 'sendDocumentCityCountry');
        $this->addQ($sec4, 'Where should the notification to pick up the travel document be sent?', 'whereToSendNotification', 'radio', [
            '8.a. To the address shown in Part 2.',
            '8.b. To the address shown below in Part 4., Item Number 9.a.'
        ]);
        $this->addQ($sec4, '9.a. In Care Of Name (if any)', 'notificationInCareOf');
        $this->addQ($sec4, 'Street Number and Name', 'notificationStreet');
        $this->addQ($sec4, 'Apt/Flr/Ste', 'notificationApt');
        $this->addQ($sec4, 'City or Town', 'notificationCity');
        $this->addQ($sec4, 'State', 'notificationState');
        $this->addQ($sec4, 'ZIP Code', 'notificationZip');
        $this->addQ($sec4, '9.b. Daytime Phone Number', 'notificationPhone');
        $this->addQ($sec4, '9.c. Email Address', 'notificationEmail');

        // Part 5. Reentry Permit
        $sec5 = $form->sections()->create(['title' => 'Part 5. Complete Only If Applying for a Reentry Permit', 'order' => 5]);
        $this->addQ($sec5, '1. Since becoming a permanent resident of the United States (or during the past 5 years, whichever is less), how much total time have you spent outside the United States?', 'timeOutsideUs', 'radio', [
            'Less Than 6 Months', '6 Months to 1 Year', '1 to 2 Years', '2 to 3 Years', '3 to 4 Years', 'More Than 4 Years'
        ]);

        // Part 6. Refugee Travel Document
        $sec6 = $form->sections()->create(['title' => 'Part 6. Complete Only If Applying for a Refugee Travel Document', 'order' => 6]);
        $this->addQ($sec6, '1. Country from which you are a refugee or asylee:', 'refugeeCountry');
        $this->addQ($sec6, '2. Do you plan to travel to the country named above in Item Number 1.?', 'planToTravelToRefugeeCountry', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '3.a. Returned to the country named above in Item Number 1.?', 'returnedToRefugeeCountry', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '3.b. Applied for and/or obtained a national passport, passport renewal, or entry permit from the country in Item Number 1.?', 'appliedForPassportRefugeeCountry', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '3.c. Applied for and/or received any benefit from the country named in Item Number 1.?', 'receivedBenefitRefugeeCountry', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '4.a. Acquired a new nationality?', 'acquiredNewNationality', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '4.b. Reacquired the nationality of the country named above in Item Number 1.?', 'reacquiredNationality', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '4.c. Been granted refugee or asylee status in any other country?', 'grantedRefugeeOtherCountry', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '5. Are you filing for a Refugee Travel Document before departing the United States?', 'filingBeforeDeparting', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '6.a. Are you currently outside the United States?', 'currentlyOutsideUs', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '6.b. If you answered "Yes," what is your current location (City or Town and Country)?', 'currentLocationOutsideUs');
        $this->addQ($sec6, '6.c. If you answered "Yes," what other countries have you traveled to since leaving the United States?', 'otherCountriesTraveledTo');

        // Part 7. Proposed Travel (Advance Parole)
        $sec7 = $form->sections()->create(['title' => 'Part 7. Information About Your Proposed Travel (Advance Parole Document)', 'order' => 7]);
        $this->addQ($sec7, '1. Date of Intended Departure (mm/dd/yyyy)', 'apIntendedDepartureDate', 'date');
        $this->addQ($sec7, '2. Purpose of trip.', 'apPurposeOfTrip', 'textarea');
        $this->addQ($sec7, '3. List the countries you intend to visit.', 'apCountriesToVisit', 'textarea');
        $this->addQ($sec7, '4. How many trips do you intend to use this document?', 'apNumberOfTrips', 'radio', ['One Trip', 'More than one trip']);
        $this->addQ($sec7, '5. Expected Length of Trip (in days)', 'apExpectedLengthOfTrip');

        // Part 8. Initial Parole, Parole in Place, Re-parole
        $sec8 = $form->sections()->create(['title' => 'Part 8. Initial Parole, Parole In Place, or Re-parole', 'order' => 8]);
        $this->addQ($sec8, '1. Explain how you qualify for parole, parole in place, or re-parole.', 'paroleExplanation', 'textarea');
        $this->addQ($sec8, '2. Expected Length of Stay in the United States', 'paroleExpectedLengthOfStay');
        $this->addQ($sec8, '3.a. Date of Intended Arrival to the United States (mm/dd/yyyy)', 'paroleIntendedArrivalDate', 'date');
        $this->addQ($sec8, '3.b. Location (City/Town and Country) of the U.S. Embassy, U.S. Consulate, or USCIS international field office that you want us to notify.', 'paroleNotifyLocation');

        // Part 9. Employment Authorization For New Period of Parole
        $sec9 = $form->sections()->create(['title' => 'Part 9. Employment Authorization For New Period of Parole (Re-parole)', 'order' => 9]);
        $this->addQ($sec9, '1. I am requesting an Employment Authorization Document (EAD) upon approval of my new period of parole (re-parole)', 'requestingEad', 'checkbox', ['Yes']);

        // Part 10. Applicant's Contact Information
        $sec10 = $form->sections()->create(['title' => 'Part 10. Applicant\'s Contact Information, Certification, and Signature', 'order' => 10]);
        $this->addQ($sec10, '1. Applicant\'s Daytime Telephone Number', 'applicantPhone');
        $this->addQ($sec10, '2. Applicant Mobile Telephone Number (if any)', 'applicantMobile');
        $this->addQ($sec10, '3. Applicant\'s Email Address (if any)', 'applicantEmail');
        $this->addQ($sec10, 'I certify, under penalty of perjury, that I provided or authorized all of the responses and information contained in and submitted with my application...', 'applicantCert', 'checkbox', ['I agree']);
        $this->addQ($sec10, '4. Applicant\'s Signature', 'applicantSignature', 'signature');
        $this->addQ($sec10, 'Date of Signature (mm/dd/yyyy)', 'applicantSignatureDate', 'date');

        // Part 11. Interpreter's Contact Information
        $sec11 = $form->sections()->create(['title' => 'Part 11. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 11]);
        $this->addQ($sec11, '1. Interpreter\'s Full Name: Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec11, 'Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec11, '2. Interpreter\'s Business or Organization Name (if any)', 'interpreterBusiness');
        $this->addQ($sec11, '3. Interpreter\'s Daytime Telephone Number', 'interpreterPhone');
        $this->addQ($sec11, '4. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobile');
        $this->addQ($sec11, '5. Interpreter\'s Email Address (if any)', 'interpreterEmail');

        // Part 12. Preparer's Contact Information
        $sec12 = $form->sections()->create(['title' => 'Part 12. Preparer\'s Contact Information, Certification, and Signature', 'order' => 12]);
        $this->addQ($sec12, '1. Preparer\'s Full Name: Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec12, 'Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec12, '2. Preparer\'s Business or Organization Name', 'preparerBusiness');
        $this->addQ($sec12, '3. Preparer\'s Daytime Telephone Number', 'preparerPhone');
        $this->addQ($sec12, '4. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobile');
        $this->addQ($sec12, '5. Preparer\'s Email Address (if any)', 'preparerEmail');

        // Part 13. Additional Information
        $sec13 = $form->sections()->create(['title' => 'Part 13. Additional Information', 'order' => 13]);
        $this->addQ($sec13, 'Additional Information', 'additionalInformation', 'textarea');

        echo "Successfully seeded I-131 form!\n";
    }

    private function addQ($section, $text, $name, $type = 'text', $options = [], $required = false) {
        $q = $section->questions()->create([
            'question_text' => $text,
            'field_name' => $name,
            'field_type' => $type,
            'is_required' => $required,
            'order' => $section->questions()->count() + 1
        ]);
        if (!empty($options)) {
            foreach ($options as $idx => $opt) {
                $q->options()->create([
                    'option_label' => $opt,
                    'option_value' => $opt,
                    'order' => $idx + 1
                ]);
            }
        }
        return $q;
    }
}
