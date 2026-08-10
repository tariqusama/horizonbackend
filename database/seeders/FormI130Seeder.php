<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI130Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-130%')->orWhere('subtitle', 'like', '%I-130%')->first();
        if (!$service) {
            echo "Service I-130 not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-130'],
            ['name' => 'Petition for Alien Relative', 'description' => 'Form I-130']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Relationship
        $sec1 = $form->sections()->create(['title' => 'Part 1. Relationship (You are the Petitioner. Your relative is the Beneficiary)', 'order' => 1]);
        $this->addQ($sec1, '1. I am filing this petition for my (Select only one box):', 'filingFor', 'radio', ['Spouse', 'Parent', 'Brother/Sister', 'Child']);
        $this->addQ($sec1, '2. If you are filing this petition for your child or parent, select the box that describes your relationship:', 'relationshipDescription', 'radio', [
            'Child was born to parents who were married to each other at the time of the child\'s birth',
            'Child was born to parents who were not married to each other at the time of the child\'s birth',
            'Stepchild/Stepparent',
            'Child was adopted (not an Orphan or Hague Convention adoptee)'
        ]);
        $this->addQ($sec1, '3. If the beneficiary is your brother/sister, are you related by adoption?', 'brotherSisterAdoption', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, '4. Did you gain lawful permanent resident status or citizenship through adoption?', 'petitionerGainedStatusAdoption', 'radio', ['Yes', 'No']);

        // Part 2. Information About You (Petitioner)
        $sec2 = $form->sections()->create(['title' => 'Part 2. Information About You (Petitioner)', 'order' => 2]);
        $this->addQ($sec2, '1. Alien Registration Number (A-Number) (if any)', 'aNumber');
        $this->addQ($sec2, '2. USCIS Online Account Number (if any)', 'uscisAccountNumber');
        $this->addQ($sec2, '3. U.S. Social Security Number (if any)', 'ssn');
        
        $this->addQ($sec2, 'Your Full Name', 'headingFullName', 'heading');
        $this->addQ($sec2, '4.a. Family Name (Last Name)', 'lastName');
        $this->addQ($sec2, '4.b. Given Name (First Name)', 'firstName');
        $this->addQ($sec2, '4.c. Middle Name', 'middleName');
        
        $this->addQ($sec2, 'Other Names Used (if any)', 'headingOtherNames', 'heading');
        $this->addQ($sec2, '5.a. Family Name (Last Name)', 'otherLastName');
        $this->addQ($sec2, '5.b. Given Name (First Name)', 'otherFirstName');
        $this->addQ($sec2, '5.c. Middle Name', 'otherMiddleName');
        
        $this->addQ($sec2, 'Other Information', 'headingOtherInfo', 'heading');
        $this->addQ($sec2, '6. City/Town/Village of Birth', 'cityOfBirth');
        $this->addQ($sec2, '7. Country of Birth', 'countryOfBirth');
        $this->addQ($sec2, '8. Date of Birth (mm/dd/yyyy)', 'dob', 'date');
        $this->addQ($sec2, '9. Sex', 'sex', 'radio', ['Male', 'Female']);
        
        $this->addQ($sec2, 'Mailing Address', 'headingMailing', 'heading');
        $this->addQ($sec2, '10.a. In Care Of Name', 'mailingInCareOf');
        $this->addQ($sec2, '10.b. Street Number and Name', 'mailingStreet');
        $this->addQ($sec2, '10.c. Apt/Ste/Flr', 'mailingApt');
        $this->addQ($sec2, '10.d. City or Town', 'mailingCity');
        $this->addQ($sec2, '10.e. State', 'mailingState');
        $this->addQ($sec2, '10.f. ZIP Code', 'mailingZip');
        $this->addQ($sec2, '10.g. Province', 'mailingProvince');
        $this->addQ($sec2, '10.h. Postal Code', 'mailingPostalCode');
        $this->addQ($sec2, '10.i. Country', 'mailingCountry');
        $this->addQ($sec2, '11. Is your current mailing address the same as your physical address?', 'mailingSameAsPhysical', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec2, 'Address History', 'headingAddressHistory', 'heading');
        $this->addQ($sec2, 'Physical Address 1', 'headingPhysical1', 'heading');
        $this->addQ($sec2, '12.a. Street Number and Name', 'physical1Street');
        $this->addQ($sec2, '12.b. Apt/Ste/Flr', 'physical1Apt');
        $this->addQ($sec2, '12.c. City or Town', 'physical1City');
        $this->addQ($sec2, '12.d. State', 'physical1State');
        $this->addQ($sec2, '12.e. ZIP Code', 'physical1Zip');
        $this->addQ($sec2, '12.f. Province', 'physical1Province');
        $this->addQ($sec2, '12.g. Postal Code', 'physical1PostalCode');
        $this->addQ($sec2, '12.h. Country', 'physical1Country');
        $this->addQ($sec2, '13.a. Date From (mm/dd/yyyy)', 'physical1DateFrom', 'date');
        $this->addQ($sec2, '13.b. Date To (mm/dd/yyyy)', 'physical1DateTo', 'date');
        
        $this->addQ($sec2, 'Physical Address 2', 'headingPhysical2', 'heading');
        $this->addQ($sec2, '14.a. Street Number and Name', 'physical2Street');
        $this->addQ($sec2, '14.b. Apt/Ste/Flr', 'physical2Apt');
        $this->addQ($sec2, '14.c. City or Town', 'physical2City');
        $this->addQ($sec2, '14.d. State', 'physical2State');
        $this->addQ($sec2, '14.e. ZIP Code', 'physical2Zip');
        $this->addQ($sec2, '14.f. Province', 'physical2Province');
        $this->addQ($sec2, '14.g. Postal Code', 'physical2PostalCode');
        $this->addQ($sec2, '14.h. Country', 'physical2Country');
        $this->addQ($sec2, '15.a. Date From (mm/dd/yyyy)', 'physical2DateFrom', 'date');
        $this->addQ($sec2, '15.b. Date To (mm/dd/yyyy)', 'physical2DateTo', 'date');

        $this->addQ($sec2, 'Your Marital Information', 'headingMaritalInfo', 'heading');
        $this->addQ($sec2, '16. How many times have you been married?', 'numberOfMarriages');
        $this->addQ($sec2, '17. Current Marital Status', 'maritalStatus', 'radio', ['Single, Never Married', 'Married', 'Divorced', 'Widowed', 'Separated', 'Annulled']);
        $this->addQ($sec2, '18. Date of Current Marriage (if currently married) (mm/dd/yyyy)', 'dateOfCurrentMarriage', 'date');
        $this->addQ($sec2, '19. Place of Your Current Marriage (if married): City, State, Province, Country', 'placeOfCurrentMarriage', 'textarea');

        $this->addQ($sec2, 'Names of All Your Spouses (if any)', 'headingSpouses', 'heading');
        $this->addQ($sec2, 'Spouse 1: Family, Given, Middle', 'spouse1Name');
        $this->addQ($sec2, '21. Date Marriage Ended (mm/dd/yyyy)', 'spouse1EndDate', 'date');
        $this->addQ($sec2, 'Spouse 2: Family, Given, Middle', 'spouse2Name');
        $this->addQ($sec2, '23. Date Marriage Ended (mm/dd/yyyy)', 'spouse2EndDate', 'date');

        $this->addQ($sec2, 'Information About Your Parents', 'headingParentsInfo', 'heading');
        $this->addQ($sec2, 'Parent 1\'s Information: Family, Given, Middle', 'parent1Name');
        $this->addQ($sec2, '25. Date of Birth (mm/dd/yyyy)', 'parent1Dob', 'date');
        $this->addQ($sec2, '26. Sex', 'parent1Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec2, '27. Country of Birth', 'parent1CountryOfBirth');
        $this->addQ($sec2, '28. City/Town/Village of Residence', 'parent1CityOfResidence');
        $this->addQ($sec2, '29. Country of Residence', 'parent1CountryOfResidence');

        $this->addQ($sec2, 'Parent 2\'s Information: Family, Given, Middle', 'parent2Name');
        $this->addQ($sec2, '31. Date of Birth (mm/dd/yyyy)', 'parent2Dob', 'date');
        $this->addQ($sec2, '32. Sex', 'parent2Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec2, '33. Country of Birth', 'parent2CountryOfBirth');
        $this->addQ($sec2, '34. City/Town/Village of Residence', 'parent2CityOfResidence');
        $this->addQ($sec2, '35. Country of Residence', 'parent2CountryOfResidence');

        $this->addQ($sec2, 'Additional Information About You (Petitioner)', 'headingAddlInfo', 'heading');
        $this->addQ($sec2, '36. I am a (Select only one box):', 'petitionerStatus', 'radio', ['U.S. Citizen', 'Lawful Permanent Resident']);
        $this->addQ($sec2, '37. If U.S. citizen, my citizenship was acquired through:', 'citizenAcquiredThrough', 'radio', ['Birth in the United States', 'Naturalization', 'Parents']);
        $this->addQ($sec2, '38. Have you obtained a Certificate of Naturalization or Citizenship?', 'hasCertificate', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, '39.a. Certificate Number', 'certificateNumber');
        $this->addQ($sec2, '39.b. Place of Issuance', 'certificatePlace');
        $this->addQ($sec2, '39.c. Date of Issuance (mm/dd/yyyy)', 'certificateDate', 'date');
        
        $this->addQ($sec2, 'If you are a lawful permanent resident, complete Item Numbers 40.a. - 41.', 'headingLprInfo', 'heading');
        $this->addQ($sec2, '40.a. Class of Admission', 'lprCoa');
        $this->addQ($sec2, '40.b. Date of Admission (mm/dd/yyyy)', 'lprDateOfAdmission', 'date');
        $this->addQ($sec2, '40.c. Place of Admission', 'lprPlaceOfAdmission');
        $this->addQ($sec2, '41. Did you gain LPR status through marriage to a U.S. citizen or LPR?', 'lprGainedThroughMarriage', 'radio', ['Yes', 'No']);

        $this->addQ($sec2, 'Employment History', 'headingEmploymentHistory', 'heading');
        $this->addQ($sec2, 'Employer 1: Name of Employer/Company', 'employer1Name');
        $this->addQ($sec2, 'Employer 1: Address (Street, Apt, City, State, ZIP, Province, Postal Code, Country)', 'employer1Address', 'textarea');
        $this->addQ($sec2, '44. Your Occupation', 'employer1Occupation');
        $this->addQ($sec2, '45.a. Date From (mm/dd/yyyy)', 'employer1DateFrom', 'date');
        $this->addQ($sec2, '45.b. Date To (mm/dd/yyyy)', 'employer1DateTo', 'date');
        
        $this->addQ($sec2, 'Employer 2: Name of Employer/Company', 'employer2Name');
        $this->addQ($sec2, 'Employer 2: Address (Street, Apt, City, State, ZIP, Province, Postal Code, Country)', 'employer2Address', 'textarea');
        $this->addQ($sec2, '48. Your Occupation', 'employer2Occupation');
        $this->addQ($sec2, '49.a. Date From (mm/dd/yyyy)', 'employer2DateFrom', 'date');
        $this->addQ($sec2, '49.b. Date To (mm/dd/yyyy)', 'employer2DateTo', 'date');

        // Part 3. Biographic Information
        $sec3 = $form->sections()->create(['title' => 'Part 3. Biographic Information', 'order' => 3]);
        $this->addQ($sec3, '1. Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec3, '2. Race (Select all applicable boxes)', 'race', 'checkbox', [
            'White', 'Asian', 'Black or African American', 'American Indian or Alaska Native', 'Native Hawaiian or Other Pacific Islander'
        ]);
        $this->addQ($sec3, '3. Height (Feet and Inches)', 'height');
        $this->addQ($sec3, '4. Weight (Pounds)', 'weight');
        $this->addQ($sec3, '5. Eye Color (Select only one box)', 'eyeColor', 'select', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec3, '6. Hair Color (Select only one box)', 'hairColor', 'select', ['Bald (No hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);

        // Part 4. Information About Beneficiary
        $sec4 = $form->sections()->create(['title' => 'Part 4. Information About Beneficiary', 'order' => 4]);
        $this->addQ($sec4, '1. Alien Registration Number (A-Number) (if any)', 'benANumber');
        $this->addQ($sec4, '2. USCIS Online Account Number (if any)', 'benUscisNumber');
        $this->addQ($sec4, '3. U.S. Social Security Number (if any)', 'benSsn');
        
        $this->addQ($sec4, 'Beneficiary\'s Full Name', 'headingBenFullName', 'heading');
        $this->addQ($sec4, '4.a. Family Name (Last Name)', 'benLastName');
        $this->addQ($sec4, '4.b. Given Name (First Name)', 'benFirstName');
        $this->addQ($sec4, '4.c. Middle Name', 'benMiddleName');
        
        $this->addQ($sec4, 'Other Names Used (if any)', 'headingBenOtherNames', 'heading');
        $this->addQ($sec4, '5.a. Family Name (Last Name)', 'benOtherLastName');
        $this->addQ($sec4, '5.b. Given Name (First Name)', 'benOtherFirstName');
        $this->addQ($sec4, '5.c. Middle Name', 'benOtherMiddleName');
        
        $this->addQ($sec4, 'Other Information About Beneficiary', 'headingBenOtherInfo', 'heading');
        $this->addQ($sec4, '6. City/Town/Village of Birth', 'benCityOfBirth');
        $this->addQ($sec4, '7. Country of Birth', 'benCountryOfBirth');
        $this->addQ($sec4, '8. Date of Birth (mm/dd/yyyy)', 'benDob', 'date');
        $this->addQ($sec4, '9. Sex', 'benSex', 'radio', ['Male', 'Female']);
        $this->addQ($sec4, '10. Has anyone else ever filed a petition for the beneficiary?', 'anyoneElseFiled', 'radio', ['Yes', 'No', 'Unknown']);
        
        $this->addQ($sec4, 'Beneficiary\'s Physical Address', 'headingBenPhysical', 'heading');
        $this->addQ($sec4, '11.a-h. Physical Address (Street, Apt, City, State, ZIP, Province, Postal Code, Country)', 'benPhysicalAddress', 'textarea');
        $this->addQ($sec4, 'Provide the address in the United States where the beneficiary intends to live', 'headingBenUsAddress', 'heading');
        $this->addQ($sec4, '12.a-e. U.S. Intended Address', 'benUsAddress', 'textarea');
        $this->addQ($sec4, 'Provide the beneficiary\'s address outside the United States', 'headingBenForeignAddress', 'heading');
        $this->addQ($sec4, '13.a-f. Foreign Address', 'benForeignAddress', 'textarea');
        
        $this->addQ($sec4, 'Other Address and Contact Information', 'headingBenContact', 'heading');
        $this->addQ($sec4, '14. Daytime Telephone Number (if any)', 'benDaytimePhone');
        $this->addQ($sec4, '15. Mobile Telephone Number (if any)', 'benMobilePhone');
        $this->addQ($sec4, '16. Email Address (if any)', 'benEmail');
        
        $this->addQ($sec4, 'Beneficiary\'s Marital Information', 'headingBenMarital', 'heading');
        $this->addQ($sec4, '17. How many times has the beneficiary been married?', 'benNumberOfMarriages');
        $this->addQ($sec4, '18. Current Marital Status', 'benMaritalStatus', 'radio', ['Single, Never Married', 'Married', 'Divorced', 'Widowed', 'Separated', 'Annulled']);
        $this->addQ($sec4, '19. Date of Current Marriage (mm/dd/yyyy)', 'benDateOfMarriage', 'date');
        $this->addQ($sec4, '20. Place of Beneficiary\'s Current Marriage', 'benPlaceOfMarriage', 'textarea');
        
        $this->addQ($sec4, 'Names of Beneficiary\'s Spouses (if any)', 'headingBenSpouses', 'heading');
        $this->addQ($sec4, 'Spouse 1: Family, Given, Middle', 'benSpouse1Name');
        $this->addQ($sec4, '22. Date Marriage Ended (mm/dd/yyyy)', 'benSpouse1EndDate', 'date');
        $this->addQ($sec4, 'Spouse 2: Family, Given, Middle', 'benSpouse2Name');
        $this->addQ($sec4, '24. Date Marriage Ended (mm/dd/yyyy)', 'benSpouse2EndDate', 'date');

        $this->addQ($sec4, 'Information About Beneficiary\'s Family', 'headingBenFamily', 'heading');
        $this->addQ($sec4, 'Person 1: Full Name, Relationship, Date of Birth, Country of Birth', 'benFamilyPerson1', 'textarea');
        $this->addQ($sec4, 'Person 2: Full Name, Relationship, Date of Birth, Country of Birth', 'benFamilyPerson2', 'textarea');
        $this->addQ($sec4, 'Person 3: Full Name, Relationship, Date of Birth, Country of Birth', 'benFamilyPerson3', 'textarea');
        $this->addQ($sec4, 'Person 4: Full Name, Relationship, Date of Birth, Country of Birth', 'benFamilyPerson4', 'textarea');
        $this->addQ($sec4, 'Person 5: Full Name, Relationship, Date of Birth, Country of Birth', 'benFamilyPerson5', 'textarea');

        $this->addQ($sec4, 'Beneficiary\'s Entry Information', 'headingBenEntry', 'heading');
        $this->addQ($sec4, '45. Was the beneficiary EVER in the United States?', 'benEverInUs', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '46.a. Class of Admission', 'benCoa');
        $this->addQ($sec4, '46.b. Form I-94 Arrival-Departure Record Number', 'benI94');
        $this->addQ($sec4, '46.c. Date of Arrival (mm/dd/yyyy)', 'benDateOfArrival', 'date');
        $this->addQ($sec4, '46.d. Date authorized stay expired', 'benStayExpired');
        $this->addQ($sec4, '47. Passport Number', 'benPassportNum');
        $this->addQ($sec4, '48. Travel Document Number', 'benTravelDocNum');
        $this->addQ($sec4, '49. Country of Issuance for Passport or Travel Document', 'benPassportCountry');
        $this->addQ($sec4, '50. Expiration Date for Passport or Travel Document (mm/dd/yyyy)', 'benPassportExpiry', 'date');
        
        $this->addQ($sec4, 'Beneficiary\'s Employment Information', 'headingBenEmployment', 'heading');
        $this->addQ($sec4, '51.a-i. Name of Current Employer and Address', 'benEmployer', 'textarea');
        $this->addQ($sec4, '52. Date Employment Began (mm/dd/yyyy)', 'benDateEmploymentBegan', 'date');
        
        $this->addQ($sec4, 'Additional Information About Beneficiary', 'headingBenAddlInfo', 'heading');
        $this->addQ($sec4, '53. Was the beneficiary EVER in immigration proceedings?', 'benEverInProceedings', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '54. Type of proceedings:', 'benProceedingType', 'radio', ['Removal', 'Exclusion/Deportation', 'Rescission', 'Other Judicial Proceedings']);
        $this->addQ($sec4, '55-56. Location and Date of proceedings', 'benProceedingLocationDate', 'textarea');
        
        $this->addQ($sec4, '57-58. If native written language does not use Roman letters, print name and foreign address:', 'benNativeNameAddress', 'textarea');
        $this->addQ($sec4, '59-60. If filing for your spouse, provide the last address at which you physically lived together and dates:', 'benLastAddressLivedTogether', 'textarea');
        
        $this->addQ($sec4, '61. The beneficiary is in the United States and will apply for adjustment of status at USCIS office in (City/State):', 'benAosLocation', 'textarea');
        $this->addQ($sec4, '62. The beneficiary will apply for an immigrant visa abroad at the U.S. Embassy or U.S. Consulate in (City/Province/Country):', 'benConsulateLocation', 'textarea');

        // Part 5. Other Information
        $sec5 = $form->sections()->create(['title' => 'Part 5. Other Information', 'order' => 5]);
        $this->addQ($sec5, '1. Have you EVER previously filed a petition for this beneficiary or any other alien?', 'previouslyFiledPetition', 'radio', ['Yes', 'No']);
        $this->addQ($sec5, '2-5. If Yes, provide name, place, date of filing, and result', 'previousPetitionDetails', 'textarea');
        $this->addQ($sec5, '6-9. If you are also submitting separate petitions for other relatives, provide names and relationships', 'otherSeparatePetitions', 'textarea');

        // Part 6, 7, 8: Certifications
        $sec6 = $form->sections()->create(['title' => 'Part 6, 7, & 8. Statements and Certifications', 'order' => 6]);
        $this->addQ($sec6, 'Petitioner\'s Statement', 'petitionerStatement', 'radio', [
            'I can read and understand English, and I have read and understand every question...',
            'The interpreter named in Part 7 read to me every question...',
            'At my request, the preparer named in Part 8 prepared this petition for me...'
        ]);
        $this->addQ($sec6, 'Petitioner\'s Daytime Telephone', 'petitionerDaytimePhone');
        $this->addQ($sec6, 'Petitioner\'s Mobile Telephone', 'petitionerMobilePhone');
        $this->addQ($sec6, 'Petitioner\'s Email Address', 'petitionerEmail');
        $this->addQ($sec6, 'Petitioner\'s Certification (I certify under penalty of perjury...)', 'petitionerCert', 'checkbox', ['I agree']);
        $this->addQ($sec6, 'Petitioner\'s Signature', 'petitionerSignature', 'signature');
        $this->addQ($sec6, 'Date of Signature (mm/dd/yyyy)', 'petitionerSignatureDate', 'date');
        
        $this->addQ($sec6, 'Interpreter Information (if applicable)', 'interpreterInfo', 'textarea');
        $this->addQ($sec6, 'Preparer Information (if applicable)', 'preparerInfo', 'textarea');

        // Part 9. Additional Information
        $sec9 = $form->sections()->create(['title' => 'Part 9. Additional Information', 'order' => 7]);
        $this->addQ($sec9, 'Additional Information', 'additionalInformation', 'textarea');

        echo "Successfully seeded I-130 form!\n";
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