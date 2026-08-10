<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI751Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-751%')->orWhere('subtitle', 'like', '%I-751%')->first();
        if (!$service) {
            echo "Service I-751 not found. (Warning: Ensure the service exists if you want to link it)\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-751'],
            ['name' => 'Petition to Remove Conditions on Residence', 'description' => 'Form I-751']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Information About You, the Conditional Resident
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About You, the Conditional Resident', 'order' => 1]);
        $this->addQ($sec1, '1.a. Family Name (Last Name)', 'conditionalResidentFamilyName');
        $this->addQ($sec1, '1.b. Given Name (First Name)', 'conditionalResidentGivenName');
        $this->addQ($sec1, '1.c. Middle Name', 'conditionalResidentMiddleName');

        $this->addQ($sec1, 'Other Names Used', 'headingOtherNames', 'heading');
        $this->addQ($sec1, 'List all other names you have ever used, including aliases, maiden name, and nicknames. If you need extra space to complete this section, use the space provided in Part 11. Additional Information.', 'headingOtherNamesNote', 'heading');
        $this->addQ($sec1, '2.a. Family Name (Last Name)', 'otherName1FamilyName');
        $this->addQ($sec1, '2.b. Given Name (First Name)', 'otherName1GivenName');
        $this->addQ($sec1, '2.c. Middle Name', 'otherName1MiddleName');
        $this->addQ($sec1, '3.a. Family Name (Last Name)', 'otherName2FamilyName');
        $this->addQ($sec1, '3.b. Given Name (First Name)', 'otherName2GivenName');
        $this->addQ($sec1, '3.c. Middle Name', 'otherName2MiddleName');

        $this->addQ($sec1, 'Other Information', 'headingOtherInformation', 'heading');
        $this->addQ($sec1, '4. Date of Birth (mm/dd/yyyy)', 'conditionalResidentDob', 'date');
        $this->addQ($sec1, '5. Country of Birth', 'conditionalResidentCountryOfBirth');
        $this->addQ($sec1, '6. Country of Citizenship or Nationality (provide all that apply)', 'conditionalResidentCountryOfCitizenship');
        $this->addQ($sec1, '7. Alien Registration Number (A-Number) (if any)', 'conditionalResidentANumber');
        $this->addQ($sec1, '8. U.S. Social Security Number (if any)', 'conditionalResidentSsn');
        $this->addQ($sec1, '9. USCIS Online Account Number (if any)', 'conditionalResidentUscisNumber');

        $this->addQ($sec1, 'Marital Status', 'headingMaritalStatus', 'heading');
        $this->addQ($sec1, '10. Marital Status', 'conditionalResidentMaritalStatus', 'radio', ['Single', 'Married', 'Divorced', 'Widowed']);
        $this->addQ($sec1, '11. Date of Marriage (mm/dd/yyyy)', 'conditionalResidentDateOfMarriage', 'date');
        $this->addQ($sec1, '12. Place of Marriage', 'conditionalResidentPlaceOfMarriage');
        $this->addQ($sec1, '13. If the marriage through which you gained conditional residence has ended, provide the date it ended (date of divorce or date of death) (mm/dd/yyyy)', 'conditionalResidentMarriageEndedDate', 'date');
        $this->addQ($sec1, '14. Conditional Residence Expires On (mm/dd/yyyy)', 'conditionalResidenceExpires', 'date');

        $this->addQ($sec1, 'Mailing Address', 'headingMailingAddress', 'heading');
        $this->addQ($sec1, '15.a. In Care Of Name', 'conditionalResidentMailingInCareOf');
        $this->addQ($sec1, '15.b. Street Number and Name', 'conditionalResidentMailingStreet');
        $this->addQ($sec1, '15.c. Apt. Ste. Flr.', 'conditionalResidentMailingAptSteFlr');
        $this->addQ($sec1, '15.d. City or Town', 'conditionalResidentMailingCity');
        $this->addQ($sec1, '15.e. State', 'conditionalResidentMailingState');
        $this->addQ($sec1, '15.f. ZIP Code', 'conditionalResidentMailingZip');

        $this->addQ($sec1, 'Physical Address', 'headingPhysicalAddress', 'heading');
        $this->addQ($sec1, '16. Is your physical address different than your mailing address?', 'conditionalResidentPhysicalDiff', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes" to Item Number 16., provide your physical address below.', 'headingPhysicalAddressNote', 'heading');
        $this->addQ($sec1, '17.a. In Care Of Name', 'conditionalResidentPhysicalInCareOf');
        $this->addQ($sec1, '17.b. Street Number and Name', 'conditionalResidentPhysicalStreet');
        $this->addQ($sec1, '17.c. Apt. Ste. Flr.', 'conditionalResidentPhysicalAptSteFlr');
        $this->addQ($sec1, '17.d. City or Town', 'conditionalResidentPhysicalCity');
        $this->addQ($sec1, '17.e. State', 'conditionalResidentPhysicalState');
        $this->addQ($sec1, '17.f. ZIP Code', 'conditionalResidentPhysicalZip');

        $this->addQ($sec1, 'Additional Information About You', 'headingAdditionalInfoYou', 'heading');
        $this->addQ($sec1, '18. Are you in removal, deportation, or rescission proceedings?', 'inRemovalProceedings', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, '19. Was a fee paid to anyone other than an attorney in connection with this petition?', 'feePaidToAnyone', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, '20. Have you ever been arrested, detained, charged, indicted, fined, or imprisoned for breaking or violating any law or ordinance (excluding traffic regulations), or committed any crime which you were not arrested in the United States or abroad?', 'everArrested', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes" to Item Number 20., provide a detailed explanation in Part 11. Additional Information or on a separate sheet of paper...', 'headingArrestNote', 'heading');
        $this->addQ($sec1, '21. If you are married, is this a different marriage than the one through which you gained conditional resident status?', 'isDifferentMarriage', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, '22. Have you resided at any other address since you became a permanent resident?', 'residedAtOtherAddress', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes" to Item Number 22., provide a list of all addresses where you have resided since becoming a permanent resident and the dates you resided at those locations in the space provided in Part 11. Additional Information.', 'headingOtherAddressNote', 'heading');
        $this->addQ($sec1, '23. Is your spouse or parent\'s spouse currently serving with or employed by the U.S. Government and serving outside the United States?', 'spouseServingUSGov', 'radio', ['Yes', 'No']);

        // Part 2. Biographic Information
        $sec2 = $form->sections()->create(['title' => 'Part 2. Biographic Information', 'order' => 2]);
        $this->addQ($sec2, '1. Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec2, '2. Race (Select all applicable boxes)', 'race', 'checkbox', ['American Indian or Alaska Native', 'Asian', 'Black or African American', 'Native Hawaiian or Other Pacific Islander', 'White']);
        $this->addQ($sec2, '3. Height: Feet', 'heightFeet', 'number');
        $this->addQ($sec2, '3. Height: Inches', 'heightInches', 'number');
        $this->addQ($sec2, '4. Weight: Pounds', 'weightPounds', 'number');
        $this->addQ($sec2, '5. Eye Color (Select only one box)', 'eyeColor', 'radio', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec2, '6. Hair Color (Select only one box)', 'hairColor', 'radio', ['Bald (No hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);

        // Part 3. Basis for Petition
        $sec3 = $form->sections()->create(['title' => 'Part 3. Basis for Petition', 'order' => 3]);
        $this->addQ($sec3, 'Joint Filing', 'headingJointFiling', 'heading');
        $this->addQ($sec3, 'My conditional residence is based on my marriage or my parent\'s marriage to a U.S. citizen or lawful permanent resident, and I am filing this joint petition together with (Select only one box):', 'jointFilingBasis', 'radio', [
            '1.a. My spouse.',
            '1.b. My parent\'s spouse because I am unable to be included in a joint petition filed by my parent and my parent\'s spouse.'
        ]);
        
        $this->addQ($sec3, 'OR Waiver or Individual Filing Request', 'headingWaiverFiling', 'heading');
        $this->addQ($sec3, 'My conditional residence is based on my marriage or my parent\'s marriage to a U.S. citizen or lawful permanent resident, I am unable to file a joint petition with my spouse or my parent\'s spouse, because (Select all applicable boxes in the next section.):', 'waiverFilingBasis', 'checkbox', [
            '1.c. My spouse is deceased.',
            '1.d. My marriage was entered in good faith, but the marriage was terminated through divorce or annulment.',
            '1.e. I entered the marriage in good faith, and, during the marriage, I was battered, or was the subject of extreme cruelty, by my U.S. citizen or lawful permanent resident spouse.',
            '1.f. My parent entered the marriage in good faith, and, during the marriage, I was battered, or was subjected to extreme cruelty, by my parent\'s U.S. citizen or lawful permanent resident spouse or by my conditional resident parent.',
            '1.g. The termination of my status and removal from the United States would result in an extreme hardship.'
        ]);

        // Part 4. Information About the U.S. Citizen or Lawful Permanent Resident Spouse
        $sec4 = $form->sections()->create(['title' => 'Part 4. Information About the U.S. Citizen or Lawful Permanent Resident Spouse...', 'order' => 4]);
        $this->addQ($sec4, 'Relationship', 'headingSpouseRelationship', 'heading');
        $this->addQ($sec4, 'Select Relationship:', 'spouseRelationship', 'radio', [
            '1.a. Spouse or Former Spouse',
            '1.b. Parent\'s Spouse or Former Spouse'
        ]);
        $this->addQ($sec4, '2.a. Family Name (Last Name)', 'spouseFamilyName');
        $this->addQ($sec4, '2.b. Given Name (First Name)', 'spouseGivenName');
        $this->addQ($sec4, '2.c. Middle Name', 'spouseMiddleName');
        
        $this->addQ($sec4, 'Other Information', 'headingSpouseOtherInfo', 'heading');
        $this->addQ($sec4, '3. Date of Birth (mm/dd/yyyy)', 'spouseDob', 'date');
        $this->addQ($sec4, '4. U.S. Social Security Number (if any)', 'spouseSsn');
        $this->addQ($sec4, '5. A-Number (if any)', 'spouseANumber');

        $this->addQ($sec4, 'Physical Address', 'headingSpousePhysicalAddress', 'heading');
        $this->addQ($sec4, '6.a. Street Number and Name', 'spousePhysicalStreet');
        $this->addQ($sec4, '6.b. Apt. Ste. Flr.', 'spousePhysicalAptSteFlr');
        $this->addQ($sec4, '6.c. City or Town', 'spousePhysicalCity');
        $this->addQ($sec4, '6.d. State', 'spousePhysicalState');
        $this->addQ($sec4, '6.e. ZIP Code', 'spousePhysicalZip');
        $this->addQ($sec4, '6.f. Province', 'spousePhysicalProvince');
        $this->addQ($sec4, '6.g. Postal Code', 'spousePhysicalPostalCode');
        $this->addQ($sec4, '6.h. Country', 'spousePhysicalCountry');

        // Part 5. Information About Your Children
        $sec5 = $form->sections()->create(['title' => 'Part 5. Information About Your Children', 'order' => 5]);
        $this->addQ($sec5, 'Provide information on all of your children. If you need extra space to complete this section, use the space provided in Part 11. Additional Information.', 'headingChildrenNote', 'heading');

        for ($i = 1; $i <= 5; $i++) {
            $prefix = "child{$i}";
            $offset = ($i - 1) * 6;
            $nameIdx = 1 + $offset;
            $dobIdx = 2 + $offset;
            $aNumIdx = 3 + $offset;
            $livingIdx = 4 + $offset;
            $applyingIdx = 5 + $offset;
            $addrIdx = 6 + $offset;

            $this->addQ($sec5, "Child {$i}", "headingChild{$i}", 'heading');
            $this->addQ($sec5, "{$nameIdx}.a. Family Name (Last Name)", "{$prefix}FamilyName");
            $this->addQ($sec5, "{$nameIdx}.b. Given Name (First Name)", "{$prefix}GivenName");
            $this->addQ($sec5, "{$nameIdx}.c. Middle Name", "{$prefix}MiddleName");
            $this->addQ($sec5, "{$dobIdx}. Date of Birth (mm/dd/yyyy)", "{$prefix}Dob", 'date');
            $this->addQ($sec5, "{$aNumIdx}. A-Number (if any)", "{$prefix}ANumber");
            $this->addQ($sec5, "{$livingIdx}. Is this child living with you?", "{$prefix}LivingWithYou", 'radio', ['Yes', 'No']);
            $this->addQ($sec5, "{$applyingIdx}. Is this child applying with you?", "{$prefix}ApplyingWithYou", 'radio', ['Yes', 'No']);

            $this->addQ($sec5, 'Physical Address', "headingChild{$i}PhysicalAddress", 'heading');
            $this->addQ($sec5, "{$addrIdx}.a. Street Number and Name", "{$prefix}Street");
            $this->addQ($sec5, "{$addrIdx}.b. Apt. Ste. Flr.", "{$prefix}AptSteFlr");
            $this->addQ($sec5, "{$addrIdx}.c. City or Town", "{$prefix}City");
            $this->addQ($sec5, "{$addrIdx}.d. State", "{$prefix}State");
            $this->addQ($sec5, "{$addrIdx}.e. ZIP Code", "{$prefix}Zip");
            $this->addQ($sec5, "{$addrIdx}.f. Province", "{$prefix}Province");
            $this->addQ($sec5, "{$addrIdx}.g. Postal Code", "{$prefix}PostalCode");
            $this->addQ($sec5, "{$addrIdx}.h. Country", "{$prefix}Country");
        }

        // Part 6. Accommodations for Individuals With Disabilities and/or Impairments
        $sec6 = $form->sections()->create(['title' => 'Part 6. Accommodations for Individuals With Disabilities and/or Impairments', 'order' => 6]);
        $this->addQ($sec6, '1. Are you requesting an accommodation because of your disabilities and/or impairments?', 'accommYourself', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '2. Are you requesting an accommodation because of your spouse\'s disabilities and/or impairments?', 'accommSpouse', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '3. Are you requesting an accommodation because of your included children\'s disabilities and/or impairments?', 'accommChildren', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '4.a. I am deaf or hard of hearing and request the following accommodation:', 'accommDeaf', 'textarea');
        $this->addQ($sec6, '4.b. I am blind or have low vision and request the following accommodation:', 'accommBlind', 'textarea');
        $this->addQ($sec6, '4.c. I have another type of disability and/or impairment (Describe the nature...):', 'accommOther', 'textarea');

        // Part 7. Petitioner's Statement, Contact Information, Certification, and Signature
        $sec7 = $form->sections()->create(['title' => 'Part 7. Petitioner\'s Statement, Contact Information, Certification, and Signature', 'order' => 7]);
        $this->addQ($sec7, 'Petitioner\'s Statement', 'headingPetitionerStatement', 'heading');
        $this->addQ($sec7, '1.a. I can read and understand English, and have read and understand every question...', 'petitionerStatementEnglish', 'radio', ['Yes', 'No']);
        $this->addQ($sec7, '1.b. The interpreter named in Part 9. has also read to me every question...', 'petitionerStatementInterpreterLanguage');
        $this->addQ($sec7, '2. I have requested the services of and consented to (preparer):', 'petitionerStatementPreparer', 'radio', ['Yes', 'No']);

        $this->addQ($sec7, 'Petitioner\'s Contact Information', 'headingPetitionerContact', 'heading');
        $this->addQ($sec7, '3. Petitioner\'s Daytime Telephone Number', 'petitionerDaytimePhone');
        $this->addQ($sec7, '4. Petitioner\'s Mobile Telephone Number (if any)', 'petitionerMobilePhone');
        $this->addQ($sec7, '5. Petitioner\'s Email Address (if any)', 'petitionerEmailAddress');

        $this->addQ($sec7, 'Petitioner\'s Signature', 'headingPetitionerSignature', 'heading');
        $this->addQ($sec7, '6.a. Petitioner\'s Signature', 'petitionerSignature');
        $this->addQ($sec7, '6.b. Date of Signature (mm/dd/yyyy)', 'petitionerSignatureDate', 'date');

        // Part 8. Spouse's or Individual Listed in Part 4.'s Statement, Contact Information, Certification, and Signature
        $sec8 = $form->sections()->create(['title' => 'Part 8. Spouse\'s or Individual Listed in Part 4.\'s Statement, Contact Information, Certification, and Signature', 'order' => 8]);
        $this->addQ($sec8, 'Spouse\'s or Individual\'s Statement', 'headingSpouseStatement', 'heading');
        $this->addQ($sec8, '1.a. I can read and understand English, and have read and understand every question...', 'spouseStatementEnglish', 'radio', ['Yes', 'No']);
        $this->addQ($sec8, '1.b. The interpreter named in Part 9. has also read to me every question...', 'spouseStatementInterpreterLanguage');
        $this->addQ($sec8, '2. I have requested the services of and consented to (preparer):', 'spouseStatementPreparer', 'radio', ['Yes', 'No']);

        $this->addQ($sec8, 'Spouse\'s or Individual\'s Contact Information', 'headingSpouseContact', 'heading');
        $this->addQ($sec8, '3. Spouse\'s or Individual\'s Daytime Telephone Number', 'spouseDaytimePhone');
        $this->addQ($sec8, '4. Spouse\'s or Individual\'s Mobile Telephone Number (if any)', 'spouseMobilePhone');
        $this->addQ($sec8, '5. Spouse\'s or Individual\'s Email Address (if any)', 'spouseEmailAddress');

        $this->addQ($sec8, 'Spouse\'s or Individual\'s Signature', 'headingSpouseSignature', 'heading');
        $this->addQ($sec8, '6.a. Spouse\'s or Individual\'s Signature', 'spouseSignature');
        $this->addQ($sec8, '6.b. Date of Signature (mm/dd/yyyy)', 'spouseSignatureDate', 'date');

        // Part 9. Interpreter's Contact Information, Certification, and Signature
        $sec9 = $form->sections()->create(['title' => 'Part 9. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 9]);
        $this->addQ($sec9, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec9, '1.a. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec9, '1.b. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec9, '2. Interpreter\'s Business or Organization Name (if any)', 'interpreterBusiness');

        $this->addQ($sec9, 'Interpreter\'s Mailing Address', 'headingInterpreterMailing', 'heading');
        $this->addQ($sec9, '3.a. Street Number and Name', 'interpreterMailingStreet');
        $this->addQ($sec9, '3.b. Apt. Ste. Flr.', 'interpreterMailingAptSteFlr');
        $this->addQ($sec9, '3.c. City or Town', 'interpreterMailingCity');
        $this->addQ($sec9, '3.d. State', 'interpreterMailingState');
        $this->addQ($sec9, '3.e. ZIP Code', 'interpreterMailingZip');
        $this->addQ($sec9, '3.f. Province', 'interpreterMailingProvince');
        $this->addQ($sec9, '3.g. Postal Code', 'interpreterMailingPostalCode');
        $this->addQ($sec9, '3.h. Country', 'interpreterMailingCountry');

        $this->addQ($sec9, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec9, '4. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec9, '5. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');

        $this->addQ($sec9, 'Interpreter\'s Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec9, '6.a. Interpreter\'s Signature', 'interpreterSignature');
        $this->addQ($sec9, '6.b. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');

        // Part 10. Preparer's Contact Information, Certification, and Signature
        $sec10 = $form->sections()->create(['title' => 'Part 10. Contact Information, Statement, Certification, and Signature of the Person Preparing this Petition', 'order' => 10]);
        $this->addQ($sec10, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec10, '1.a. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec10, '1.b. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec10, '2. Preparer\'s Business or Organization Name (if any)', 'preparerBusiness');

        $this->addQ($sec10, 'Preparer\'s Mailing Address', 'headingPreparerMailing', 'heading');
        $this->addQ($sec10, '3.a. Street Number and Name', 'preparerMailingStreet');
        $this->addQ($sec10, '3.b. Apt. Ste. Flr.', 'preparerMailingAptSteFlr');
        $this->addQ($sec10, '3.c. City or Town', 'preparerMailingCity');
        $this->addQ($sec10, '3.d. State', 'preparerMailingState');
        $this->addQ($sec10, '3.e. ZIP Code', 'preparerMailingZip');
        $this->addQ($sec10, '3.f. Province', 'preparerMailingProvince');
        $this->addQ($sec10, '3.g. Postal Code', 'preparerMailingPostalCode');
        $this->addQ($sec10, '3.h. Country', 'preparerMailingCountry');

        $this->addQ($sec10, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec10, '4. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec10, '5. Preparer\'s Fax Number', 'preparerFaxPhone');
        $this->addQ($sec10, '6. Preparer\'s Email Address (if any)', 'preparerEmailAddress');

        $this->addQ($sec10, 'Preparer\'s Statement', 'headingPreparerStatement', 'heading');
        $this->addQ($sec10, '7.a. I am not an attorney or accredited representative but have prepared this petition on behalf of the petitioner and with the petitioner\'s consent.', 'preparerStatementNotAttorney', 'radio', ['Yes', 'No']);
        $this->addQ($sec10, '7.b. I am an attorney or accredited representative and my representation of the petitioner in this case', 'preparerStatementAttorney', 'radio', ['extends', 'does not extend']);

        $this->addQ($sec10, 'Preparer\'s Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec10, '8.a. Preparer\'s Signature', 'preparerSignature');
        $this->addQ($sec10, '8.b. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');

        // Part 11. Additional Information
        $sec11 = $form->sections()->create(['title' => 'Part 11. Additional Information', 'order' => 11]);
        $this->addQ($sec11, '1.a Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec11, '1.b. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec11, '1.c. Middle Name', 'additionalMiddleName');
        $this->addQ($sec11, '2. A-Number (if any)', 'additionalANumber');
        
        for ($i = 3; $i <= 7; $i++) {
            $this->addQ($sec11, "{$i}.a. Page Number", "additional{$i}Page");
            $this->addQ($sec11, "{$i}.b. Part Number", "additional{$i}Part");
            $this->addQ($sec11, "{$i}.c. Item Number", "additional{$i}Item");
            $this->addQ($sec11, "{$i}.d. Additional Information", "additional{$i}Info", 'textarea');
        }

        echo "Successfully seeded I-751 form!\n";
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