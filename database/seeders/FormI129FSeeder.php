<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI129FSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-129F%')->orWhere('subtitle', 'like', '%I-129F%')->first();
        if (!$service) {
            echo "Service I-129F not found. (Warning: Ensure the service exists if you want to link it)\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-129f'],
            ['name' => 'Petition for Alien Fiancé(e)', 'description' => 'Form I-129F']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Information About You
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About You', 'order' => 1]);
        $this->addQ($sec1, '1. Alien Registration Number (A-Number) (if any)', 'petitionerANumber');
        $this->addQ($sec1, '2. USCIS Online Account Number (if any)', 'petitionerUscisNumber');
        $this->addQ($sec1, '3. U.S. Social Security Number (if any)', 'petitionerSsn');
        
        $this->addQ($sec1, 'Select one box below to indicate the classification you are requesting for your beneficiary:', 'beneficiaryClassification', 'radio', [
            '4.a. Fiancé(e) (K-1 visa)',
            '4.b. Spouse (K-3 visa)'
        ]);
        $this->addQ($sec1, '5. If you are filing to classify your spouse as a K-3, have you filed Form I-130?', 'filedI130', 'radio', ['Yes', 'No']);

        $this->addQ($sec1, 'Your Full Name', 'headingFullName', 'heading');
        $this->addQ($sec1, '6.a. Family Name (Last Name)', 'petitionerFamilyName');
        $this->addQ($sec1, '6.b. Given Name (First Name)', 'petitionerGivenName');
        $this->addQ($sec1, '6.c. Middle Name', 'petitionerMiddleName');

        $this->addQ($sec1, 'Other Names Used', 'headingOtherNames', 'heading');
        $this->addQ($sec1, 'Provide all other names you have ever used, including aliases, maiden name, and nicknames. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingOtherNamesNote', 'heading');
        $this->addQ($sec1, '7.a. Family Name (Last Name)', 'petitionerOtherFamilyName');
        $this->addQ($sec1, '7.b. Given Name (First Name)', 'petitionerOtherGivenName');
        $this->addQ($sec1, '7.c. Middle Name', 'petitionerOtherMiddleName');

        $this->addQ($sec1, 'Your Mailing Address', 'headingMailingAddress', 'heading');
        $this->addQ($sec1, '8.a. In Care Of Name', 'petitionerMailingInCareOf');
        $this->addQ($sec1, '8.b. Street Number and Name', 'petitionerMailingStreet');
        $this->addQ($sec1, '8.c. Apt. Ste. Flr.', 'petitionerMailingAptSteFlr');
        $this->addQ($sec1, '8.d. City or Town', 'petitionerMailingCity');
        $this->addQ($sec1, '8.e. State', 'petitionerMailingState');
        $this->addQ($sec1, '8.f. ZIP Code', 'petitionerMailingZip');
        $this->addQ($sec1, '8.g. Province', 'petitionerMailingProvince');
        $this->addQ($sec1, '8.h. Postal Code', 'petitionerMailingPostalCode');
        $this->addQ($sec1, '8.i. Country', 'petitionerMailingCountry');
        $this->addQ($sec1, '8.j. Is your current mailing address the same as your physical address?', 'petitionerMailingSameAsPhysical', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "No," provide your physical address in Item Numbers 9.a. - 9.h.', 'headingMailingPhysicalNote', 'heading');

        $this->addQ($sec1, 'Your Address History', 'headingAddressHistory', 'heading');
        $this->addQ($sec1, 'Provide your physical addresses for the last five years, whether inside or outside the United States. Provide your current address first if it is different from your mailing address in Item Numbers 8.a. - 8.i. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingAddressHistoryNote', 'heading');
        
        $this->addQ($sec1, 'Physical Address 1', 'headingPhysicalAddress1', 'heading');
        $this->addQ($sec1, '9.a. Street Number and Name', 'petitionerPhysical1Street');
        $this->addQ($sec1, '9.b. Apt. Ste. Flr.', 'petitionerPhysical1AptSteFlr');
        $this->addQ($sec1, '9.c. City or Town', 'petitionerPhysical1City');
        $this->addQ($sec1, '9.d. State', 'petitionerPhysical1State');
        $this->addQ($sec1, '9.e. ZIP Code', 'petitionerPhysical1Zip');
        $this->addQ($sec1, '9.f. Province', 'petitionerPhysical1Province');
        $this->addQ($sec1, '9.g. Postal Code', 'petitionerPhysical1PostalCode');
        $this->addQ($sec1, '9.h. Country', 'petitionerPhysical1Country');
        $this->addQ($sec1, '10.a. Date From (mm/dd/yyyy)', 'petitionerPhysical1DateFrom', 'date');
        $this->addQ($sec1, '10.b. Date To (mm/dd/yyyy)', 'petitionerPhysical1DateTo', 'date');

        $this->addQ($sec1, 'Physical Address 2', 'headingPhysicalAddress2', 'heading');
        $this->addQ($sec1, '11.a. Street Number and Name', 'petitionerPhysical2Street');
        $this->addQ($sec1, '11.b. Apt. Ste. Flr.', 'petitionerPhysical2AptSteFlr');
        $this->addQ($sec1, '11.c. City or Town', 'petitionerPhysical2City');
        $this->addQ($sec1, '11.d. State', 'petitionerPhysical2State');
        $this->addQ($sec1, '11.e. ZIP Code', 'petitionerPhysical2Zip');
        $this->addQ($sec1, '11.f. Province', 'petitionerPhysical2Province');
        $this->addQ($sec1, '11.g. Postal Code', 'petitionerPhysical2PostalCode');
        $this->addQ($sec1, '11.h. Country', 'petitionerPhysical2Country');
        $this->addQ($sec1, '12.a. Date From (mm/dd/yyyy)', 'petitionerPhysical2DateFrom', 'date');
        $this->addQ($sec1, '12.b. Date To (mm/dd/yyyy)', 'petitionerPhysical2DateTo', 'date');

        $this->addQ($sec1, 'Your Employment History', 'headingEmploymentHistory', 'heading');
        $this->addQ($sec1, 'Provide your employment history for the last five years, whether inside or outside the United States. Provide your current employment first. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingEmploymentHistoryNote', 'heading');
        
        $this->addQ($sec1, 'Employer 1', 'headingEmployer1', 'heading');
        $this->addQ($sec1, '13. Full Name of Employer', 'petitionerEmp1Name');
        $this->addQ($sec1, '14.a. Street Number and Name', 'petitionerEmp1Street');
        $this->addQ($sec1, '14.b. Apt. Ste. Flr.', 'petitionerEmp1AptSteFlr');
        $this->addQ($sec1, '14.c. City or Town', 'petitionerEmp1City');
        $this->addQ($sec1, '14.d. State', 'petitionerEmp1State');
        $this->addQ($sec1, '14.e. ZIP Code', 'petitionerEmp1Zip');
        $this->addQ($sec1, '14.f. Province', 'petitionerEmp1Province');
        $this->addQ($sec1, '14.g. Postal Code', 'petitionerEmp1PostalCode');
        $this->addQ($sec1, '14.h. Country', 'petitionerEmp1Country');
        $this->addQ($sec1, '15. Your Occupation (specify)', 'petitionerEmp1Occupation');
        $this->addQ($sec1, '16.a. Employment Start Date (mm/dd/yyyy)', 'petitionerEmp1Start', 'date');
        $this->addQ($sec1, '16.b. Employment End Date (mm/dd/yyyy)', 'petitionerEmp1End', 'date');

        $this->addQ($sec1, 'Employer 2', 'headingEmployer2', 'heading');
        $this->addQ($sec1, '17. Full Name of Employer', 'petitionerEmp2Name');
        $this->addQ($sec1, '18.a. Street Number and Name', 'petitionerEmp2Street');
        $this->addQ($sec1, '18.b. Apt. Ste. Flr.', 'petitionerEmp2AptSteFlr');
        $this->addQ($sec1, '18.c. City or Town', 'petitionerEmp2City');
        $this->addQ($sec1, '18.d. State', 'petitionerEmp2State');
        $this->addQ($sec1, '18.e. ZIP Code', 'petitionerEmp2Zip');
        $this->addQ($sec1, '18.f. Province', 'petitionerEmp2Province');
        $this->addQ($sec1, '18.g. Postal Code', 'petitionerEmp2PostalCode');
        $this->addQ($sec1, '18.h. Country', 'petitionerEmp2Country');
        $this->addQ($sec1, '19. Your Occupation (specify)', 'petitionerEmp2Occupation');
        $this->addQ($sec1, '20.a. Employment Start Date (mm/dd/yyyy)', 'petitionerEmp2Start', 'date');
        $this->addQ($sec1, '20.b. Employment End Date (mm/dd/yyyy)', 'petitionerEmp2End', 'date');

        $this->addQ($sec1, 'Other Information', 'headingOtherInfo', 'heading');
        $this->addQ($sec1, '21. Sex', 'petitionerSex', 'radio', ['Male', 'Female']);
        $this->addQ($sec1, '22. Date of Birth (mm/dd/yyyy)', 'petitionerDob', 'date');
        $this->addQ($sec1, '23. Marital Status', 'petitionerMaritalStatus', 'radio', ['Single', 'Married', 'Divorced', 'Widowed']);
        $this->addQ($sec1, '24. City/Town/Village of Birth', 'petitionerCityOfBirth');
        $this->addQ($sec1, '25. Province or State of Birth', 'petitionerStateOfBirth');
        $this->addQ($sec1, '26. Country of Birth', 'petitionerCountryOfBirth');

        $this->addQ($sec1, 'Information About Your Parents', 'headingParentsInfo', 'heading');
        $this->addQ($sec1, 'Parent 1\'s Information', 'headingParent1Info', 'heading');
        $this->addQ($sec1, '27.a. Family Name (Last Name)', 'petitionerParent1FamilyName');
        $this->addQ($sec1, '27.b. Given Name (First Name)', 'petitionerParent1GivenName');
        $this->addQ($sec1, '27.c. Middle Name', 'petitionerParent1MiddleName');
        $this->addQ($sec1, '28. Date of Birth (mm/dd/yyyy)', 'petitionerParent1Dob', 'date');
        $this->addQ($sec1, '29. Sex', 'petitionerParent1Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec1, '30. Country of Birth', 'petitionerParent1CountryOfBirth');
        $this->addQ($sec1, '31.a. City/Town/Village of Residence', 'petitionerParent1CityOfResidence');
        $this->addQ($sec1, '31.b. Country of Residence', 'petitionerParent1CountryOfResidence');

        $this->addQ($sec1, 'Parent 2\'s Information', 'headingParent2Info', 'heading');
        $this->addQ($sec1, '32.a. Family Name (Last Name)', 'petitionerParent2FamilyName');
        $this->addQ($sec1, '32.b. Given Name (First Name)', 'petitionerParent2GivenName');
        $this->addQ($sec1, '32.c. Middle Name', 'petitionerParent2MiddleName');
        $this->addQ($sec1, '33. Date of Birth (mm/dd/yyyy)', 'petitionerParent2Dob', 'date');
        $this->addQ($sec1, '34. Sex', 'petitionerParent2Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec1, '35. Country of Birth', 'petitionerParent2CountryOfBirth');
        $this->addQ($sec1, '36.a. City/Town/Village of Residence', 'petitionerParent2CityOfResidence');
        $this->addQ($sec1, '36.b. Country of Residence', 'petitionerParent2CountryOfResidence');

        $this->addQ($sec1, '37. Have you ever been previously married?', 'petitionerPreviouslyMarried', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes" to Item Number 37., provide the names of each spouse and the date that each prior marriage ended in Item Numbers 38.a. - 39. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingPreviousMarriageNote', 'heading');
        $this->addQ($sec1, 'Name of Previous Spouse', 'headingPreviousSpouse', 'heading');
        $this->addQ($sec1, '38.a. Family Name (Last Name)', 'petitionerPrevSpouseFamilyName');
        $this->addQ($sec1, '38.b. Given Name (First Name)', 'petitionerPrevSpouseGivenName');
        $this->addQ($sec1, '38.c. Middle Name', 'petitionerPrevSpouseMiddleName');
        $this->addQ($sec1, '39. Date Marriage Ended (mm/dd/yyyy)', 'petitionerPrevSpouseDateEnded', 'date');

        $this->addQ($sec1, 'Your Citizenship Information', 'headingCitizenshipInfo', 'heading');
        $this->addQ($sec1, 'You are a U.S. citizen through (select only one box):', 'petitionerCitizenshipThrough', 'radio', [
            '40.a. Birth in the United States',
            '40.b. Naturalization',
            '40.c. U.S. citizen parents'
        ]);
        $this->addQ($sec1, '41. Have you obtained a Certificate of Naturalization or a Certificate of Citizenship in your own name?', 'petitionerHasCertificate', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes" to Item Number 41., complete Item Numbers 42.a. - 42.c.', 'headingCertificateNote', 'heading');
        $this->addQ($sec1, '42.a. Certificate Number', 'petitionerCertificateNumber');
        $this->addQ($sec1, '42.b. Place of Issuance', 'petitionerCertificatePlace');
        $this->addQ($sec1, '42.c. Date of Issuance (mm/dd/yyyy)', 'petitionerCertificateDate', 'date');

        $this->addQ($sec1, 'Additional Information', 'headingAdditionalInfoPart1', 'heading');
        $this->addQ($sec1, '43. Have you ever filed Form I-129F for any other beneficiary?', 'petitionerFiledI129FBefore', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes" to Item Number 43., provide the responses to Item Number 44. - 46. for each previous beneficiary. If you need to provide information for more than one beneficiary, use the space provided in Part 8. Additional Information.', 'headingPreviousBeneficiaryNote', 'heading');
        $this->addQ($sec1, '44. A-Number (if any)', 'previousBeneficiaryANumber');
        $this->addQ($sec1, '45.a. Family Name (Last Name)', 'previousBeneficiaryFamilyName');
        $this->addQ($sec1, '45.b. Given Name (First Name)', 'previousBeneficiaryGivenName');
        $this->addQ($sec1, '45.c. Middle Name', 'previousBeneficiaryMiddleName');
        $this->addQ($sec1, '46. Date of Filing (mm/dd/yyyy)', 'previousBeneficiaryDateOfFiling', 'date');
        $this->addQ($sec1, '47. What action did USCIS take on Form I-129F (for example, approved, denied, revoked)?', 'previousBeneficiaryActionTaken', 'textarea');

        $this->addQ($sec1, '48. Do you have any children under 18 years of age?', 'petitionerHasChildrenUnder18', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes" to Item Number 48., provide the ages for your children under 18 years of age in Item Numbers 49.a. - 49.b. Provide the ages for your children under 18 years of age. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingChildrenAgesNote', 'heading');
        $this->addQ($sec1, '49.a. Age', 'petitionerChild1Age');
        $this->addQ($sec1, '49.b. Age', 'petitionerChild2Age');

        $this->addQ($sec1, 'Provide all U.S. states and foreign countries in which you have resided since your 18th birthday.', 'headingResidedStatesCountries', 'heading');
        $this->addQ($sec1, 'Residence 1', 'headingResidence1', 'heading');
        $this->addQ($sec1, '50.a. State', 'petitionerResidence1State');
        $this->addQ($sec1, '50.b. Country', 'petitionerResidence1Country');
        $this->addQ($sec1, 'Residence 2', 'headingResidence2', 'heading');
        $this->addQ($sec1, '51.a. State', 'petitionerResidence2State');
        $this->addQ($sec1, '51.b. Country', 'petitionerResidence2Country');


        // Part 2. Information About Your Beneficiary
        $sec2 = $form->sections()->create(['title' => 'Part 2. Information About Your Beneficiary', 'order' => 2]);
        $this->addQ($sec2, '1.a. Family Name (Last Name)', 'beneficiaryFamilyName');
        $this->addQ($sec2, '1.b. Given Name (First Name)', 'beneficiaryGivenName');
        $this->addQ($sec2, '1.c. Middle Name', 'beneficiaryMiddleName');
        $this->addQ($sec2, '2. A-Number (if any)', 'beneficiaryANumber');
        $this->addQ($sec2, '3. U.S. Social Security Number (if any)', 'beneficiarySsn');
        $this->addQ($sec2, '4. Date of Birth (mm/dd/yyyy)', 'beneficiaryDob', 'date');
        $this->addQ($sec2, '5. Sex', 'beneficiarySex', 'radio', ['Male', 'Female']);
        $this->addQ($sec2, '6. Marital Status', 'beneficiaryMaritalStatus', 'radio', ['Single', 'Married', 'Divorced', 'Widowed']);
        $this->addQ($sec2, '7. City/Town/Village of Birth', 'beneficiaryCityOfBirth');
        $this->addQ($sec2, '8. Country of Birth', 'beneficiaryCountryOfBirth');
        $this->addQ($sec2, '9. Country of Citizenship or Nationality', 'beneficiaryCountryOfCitizenship');

        $this->addQ($sec2, 'Other Names Used', 'headingBeneficiaryOtherNames', 'heading');
        $this->addQ($sec2, 'Provide all other names you have ever used, including aliases, maiden name, and nicknames. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingBeneficiaryOtherNamesNote', 'heading');
        $this->addQ($sec2, '10.a. Family Name (Last Name)', 'beneficiaryOtherFamilyName');
        $this->addQ($sec2, '10.b. Given Name (First Name)', 'beneficiaryOtherGivenName');
        $this->addQ($sec2, '10.c. Middle Name', 'beneficiaryOtherMiddleName');

        $this->addQ($sec2, 'Mailing Address for Your Beneficiary', 'headingBeneficiaryMailingAddress', 'heading');
        $this->addQ($sec2, '11.a. In Care Of Name', 'beneficiaryMailingInCareOf');
        $this->addQ($sec2, '11.b. Street Number and Name', 'beneficiaryMailingStreet');
        $this->addQ($sec2, '11.c. Apt. Ste. Flr.', 'beneficiaryMailingAptSteFlr');
        $this->addQ($sec2, '11.d. City or Town', 'beneficiaryMailingCity');
        $this->addQ($sec2, '11.e. State', 'beneficiaryMailingState');
        $this->addQ($sec2, '11.f. ZIP Code', 'beneficiaryMailingZip');
        $this->addQ($sec2, '11.g. Province', 'beneficiaryMailingProvince');
        $this->addQ($sec2, '11.h. Postal Code', 'beneficiaryMailingPostalCode');
        $this->addQ($sec2, '11.i. Country', 'beneficiaryMailingCountry');

        $this->addQ($sec2, 'Your Beneficiary\'s Address History', 'headingBeneficiaryAddressHistory', 'heading');
        $this->addQ($sec2, 'Provide your beneficiary\'s physical addresses for the last five years, whether inside or outside the United States. Provide your beneficiary\'s current address first if it is different from the mailing address in Item Numbers 11.a. - 11.i. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingBeneficiaryAddressHistoryNote', 'heading');
        
        $this->addQ($sec2, 'Beneficiary\'s Physical Address 1', 'headingBeneficiaryPhysicalAddress1', 'heading');
        $this->addQ($sec2, '12.a. Street Number and Name', 'beneficiaryPhysical1Street');
        $this->addQ($sec2, '12.b. Apt. Ste. Flr.', 'beneficiaryPhysical1AptSteFlr');
        $this->addQ($sec2, '12.c. City or Town', 'beneficiaryPhysical1City');
        $this->addQ($sec2, '12.d. State', 'beneficiaryPhysical1State');
        $this->addQ($sec2, '12.e. ZIP Code', 'beneficiaryPhysical1Zip');
        $this->addQ($sec2, '12.f. Province', 'beneficiaryPhysical1Province');
        $this->addQ($sec2, '12.g. Postal Code', 'beneficiaryPhysical1PostalCode');
        $this->addQ($sec2, '12.h. Country', 'beneficiaryPhysical1Country');
        $this->addQ($sec2, '13.a. Date From (mm/dd/yyyy)', 'beneficiaryPhysical1DateFrom', 'date');
        $this->addQ($sec2, '13.b. Date To (mm/dd/yyyy)', 'beneficiaryPhysical1DateTo', 'date');

        $this->addQ($sec2, 'Beneficiary\'s Physical Address 2', 'headingBeneficiaryPhysicalAddress2', 'heading');
        $this->addQ($sec2, '14.a. Street Number and Name', 'beneficiaryPhysical2Street');
        $this->addQ($sec2, '14.b. Apt. Ste. Flr.', 'beneficiaryPhysical2AptSteFlr');
        $this->addQ($sec2, '14.c. City or Town', 'beneficiaryPhysical2City');
        $this->addQ($sec2, '14.d. State', 'beneficiaryPhysical2State');
        $this->addQ($sec2, '14.e. ZIP Code', 'beneficiaryPhysical2Zip');
        $this->addQ($sec2, '14.f. Province', 'beneficiaryPhysical2Province');
        $this->addQ($sec2, '14.g. Postal Code', 'beneficiaryPhysical2PostalCode');
        $this->addQ($sec2, '14.h. Country', 'beneficiaryPhysical2Country');
        $this->addQ($sec2, '15.a. Date From (mm/dd/yyyy)', 'beneficiaryPhysical2DateFrom', 'date');
        $this->addQ($sec2, '15.b. Date To (mm/dd/yyyy)', 'beneficiaryPhysical2DateTo', 'date');

        $this->addQ($sec2, 'Your Beneficiary\'s Employment History', 'headingBeneficiaryEmploymentHistory', 'heading');
        $this->addQ($sec2, 'Provide your employment history for the last five years, whether inside or outside the United States. Provide your current employment first. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingBeneficiaryEmploymentHistoryNote', 'heading');
        
        $this->addQ($sec2, 'Beneficiary\'s Employer 1', 'headingBeneficiaryEmployer1', 'heading');
        $this->addQ($sec2, '16. Full Name of Employer', 'beneficiaryEmp1Name');
        $this->addQ($sec2, '17.a. Street Number and Name', 'beneficiaryEmp1Street');
        $this->addQ($sec2, '17.b. Apt. Ste. Flr.', 'beneficiaryEmp1AptSteFlr');
        $this->addQ($sec2, '17.c. City or Town', 'beneficiaryEmp1City');
        $this->addQ($sec2, '17.d. State', 'beneficiaryEmp1State');
        $this->addQ($sec2, '17.e. ZIP Code', 'beneficiaryEmp1Zip');
        $this->addQ($sec2, '17.f. Province', 'beneficiaryEmp1Province');
        $this->addQ($sec2, '17.g. Postal Code', 'beneficiaryEmp1PostalCode');
        $this->addQ($sec2, '17.h. Country', 'beneficiaryEmp1Country');
        $this->addQ($sec2, '18. Beneficiary\'s Occupation (specify)', 'beneficiaryEmp1Occupation');
        $this->addQ($sec2, '19.a. Employment Start Date (mm/dd/yyyy)', 'beneficiaryEmp1Start', 'date');
        $this->addQ($sec2, '19.b. Employment End Date (mm/dd/yyyy)', 'beneficiaryEmp1End', 'date');

        $this->addQ($sec2, 'Beneficiary\'s Employer 2', 'headingBeneficiaryEmployer2', 'heading');
        $this->addQ($sec2, '20. Full Name of Employer', 'beneficiaryEmp2Name');
        $this->addQ($sec2, '21.a. Street Number and Name', 'beneficiaryEmp2Street');
        $this->addQ($sec2, '21.b. Apt. Ste. Flr.', 'beneficiaryEmp2AptSteFlr');
        $this->addQ($sec2, '21.c. City or Town', 'beneficiaryEmp2City');
        $this->addQ($sec2, '21.d. State', 'beneficiaryEmp2State');
        $this->addQ($sec2, '21.e. ZIP Code', 'beneficiaryEmp2Zip');
        $this->addQ($sec2, '21.f. Province', 'beneficiaryEmp2Province');
        $this->addQ($sec2, '21.g. Postal Code', 'beneficiaryEmp2PostalCode');
        $this->addQ($sec2, '21.h. Country', 'beneficiaryEmp2Country');
        $this->addQ($sec2, '22. Beneficiary\'s Occupation (specify)', 'beneficiaryEmp2Occupation');
        $this->addQ($sec2, '23.a. Employment Start Date (mm/dd/yyyy)', 'beneficiaryEmp2Start', 'date');
        $this->addQ($sec2, '23.b. Employment End Date (mm/dd/yyyy)', 'beneficiaryEmp2End', 'date');

        $this->addQ($sec2, 'Information About Your Beneficiary\'s Parents', 'headingBeneficiaryParentsInfo', 'heading');
        $this->addQ($sec2, 'Parent 1\'s Information', 'headingBeneficiaryParent1Info', 'heading');
        $this->addQ($sec2, '24.a. Family Name (Last Name)', 'beneficiaryParent1FamilyName');
        $this->addQ($sec2, '24.b. Given Name (First Name)', 'beneficiaryParent1GivenName');
        $this->addQ($sec2, '24.c. Middle Name', 'beneficiaryParent1MiddleName');
        $this->addQ($sec2, '25. Date of Birth (mm/dd/yyyy)', 'beneficiaryParent1Dob', 'date');
        $this->addQ($sec2, '26. Sex', 'beneficiaryParent1Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec2, '27. Country of Birth', 'beneficiaryParent1CountryOfBirth');
        $this->addQ($sec2, '28.a. City/Town/Village of Residence', 'beneficiaryParent1CityOfResidence');
        $this->addQ($sec2, '28.b. Country of Residence', 'beneficiaryParent1CountryOfResidence');

        $this->addQ($sec2, 'Parent 2\'s Information', 'headingBeneficiaryParent2Info', 'heading');
        $this->addQ($sec2, '29.a. Family Name (Last Name)', 'beneficiaryParent2FamilyName');
        $this->addQ($sec2, '29.b. Given Name (First Name)', 'beneficiaryParent2GivenName');
        $this->addQ($sec2, '29.c. Middle Name', 'beneficiaryParent2MiddleName');
        $this->addQ($sec2, '30. Date of Birth (mm/dd/yyyy)', 'beneficiaryParent2Dob', 'date');
        $this->addQ($sec2, '31. Sex', 'beneficiaryParent2Sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec2, '32. Country of Birth', 'beneficiaryParent2CountryOfBirth');
        $this->addQ($sec2, '33.a. City/Town/Village of Residence', 'beneficiaryParent2CityOfResidence');
        $this->addQ($sec2, '33.b. Country of Residence', 'beneficiaryParent2CountryOfResidence');

        $this->addQ($sec2, 'Other Information About Your Beneficiary', 'headingBeneficiaryOtherInfo', 'heading');
        $this->addQ($sec2, '34. Has your beneficiary ever been previously married?', 'beneficiaryPreviouslyMarried', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If you answered "Yes" to Item Number 34., provide the names of each prior spouse and the date each prior marriage ended in Item Numbers 35.a. - 36. If you need to provide information for more than one spouse, use the space provided in Part 8. Additional Information.', 'headingBeneficiaryPrevMarriageNote', 'heading');
        $this->addQ($sec2, 'Name of Previous Spouse', 'headingBeneficiaryPrevSpouse', 'heading');
        $this->addQ($sec2, '35.a. Family Name (Last Name)', 'beneficiaryPrevSpouseFamilyName');
        $this->addQ($sec2, '35.b. Given Name (First Name)', 'beneficiaryPrevSpouseGivenName');
        $this->addQ($sec2, '35.c. Middle Name', 'beneficiaryPrevSpouseMiddleName');
        $this->addQ($sec2, '36. Date Marriage Ended (mm/dd/yyyy)', 'beneficiaryPrevSpouseDateEnded', 'date');

        $this->addQ($sec2, '37. Has your beneficiary ever been in the United States?', 'beneficiaryInUSBefore', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If your beneficiary is currently in the United States, complete Item Numbers 38.a. - 38.h.', 'headingBeneficiaryInUSNote', 'heading');
        $this->addQ($sec2, '38.a. He or she last entered as a (for example, visitor, student, exchange alien, crewman, stowaway, temporary worker, without inspection):', 'beneficiaryLastEnteredAs');
        $this->addQ($sec2, '38.b. I-94 Arrival-Departure Record Number', 'beneficiaryI94Number');
        $this->addQ($sec2, '38.c. Date of Arrival (mm/dd/yyyy)', 'beneficiaryDateOfArrival', 'date');
        $this->addQ($sec2, '38.d. Date authorized stay expired or will expire as shown on Form I-94 or I-95 (mm/dd/yyyy)', 'beneficiaryDateStayExpired', 'date');
        $this->addQ($sec2, '38.e. Passport Number', 'beneficiaryPassportNumber');
        $this->addQ($sec2, '38.f. Travel Document Number', 'beneficiaryTravelDocumentNumber');
        $this->addQ($sec2, '38.g. Country of Issuance for Passport or Travel Document', 'beneficiaryCountryOfIssuance');
        $this->addQ($sec2, '38.h. Expiration Date for Passport or Travel Document (mm/dd/yyyy)', 'beneficiaryPassportExpirationDate', 'date');

        $this->addQ($sec2, 'Children of Beneficiary', 'headingBeneficiaryChildren', 'heading');
        $this->addQ($sec2, '39. Does your beneficiary have any children?', 'beneficiaryHasChildren', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If you answered "Yes" to Item Number 39., provide the following information about each child. If you need to provide information for more than one child, use the space provided in Part 8. Additional Information.', 'headingBeneficiaryChildrenNote', 'heading');
        $this->addQ($sec2, '40.a. Family Name (Last Name)', 'beneficiaryChildFamilyName');
        $this->addQ($sec2, '40.b. Given Name (First Name)', 'beneficiaryChildGivenName');
        $this->addQ($sec2, '40.c. Middle Name', 'beneficiaryChildMiddleName');
        $this->addQ($sec2, '41. Country of Birth', 'beneficiaryChildCountryOfBirth');
        $this->addQ($sec2, '42. Date of Birth (mm/dd/yyyy)', 'beneficiaryChildDob', 'date');
        $this->addQ($sec2, '43. Does this child reside with your beneficiary?', 'beneficiaryChildResidesWith', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If the child does not reside with your beneficiary, provide the child\'s physical residence.', 'headingBeneficiaryChildResidenceNote', 'heading');
        $this->addQ($sec2, '44.a. Street Number and Name', 'beneficiaryChildStreet');
        $this->addQ($sec2, '44.b. Apt. Ste. Flr.', 'beneficiaryChildAptSteFlr');
        $this->addQ($sec2, '44.c. City or Town', 'beneficiaryChildCity');
        $this->addQ($sec2, '44.d. State', 'beneficiaryChildState');
        $this->addQ($sec2, '44.e. ZIP Code', 'beneficiaryChildZip');
        $this->addQ($sec2, '44.f. Province', 'beneficiaryChildProvince');
        $this->addQ($sec2, '44.g. Postal Code', 'beneficiaryChildPostalCode');
        $this->addQ($sec2, '44.h. Country', 'beneficiaryChildCountry');

        $this->addQ($sec2, 'Address in the United States Where Your Beneficiary Intends to Live', 'headingBeneficiaryIntendsToLive', 'heading');
        $this->addQ($sec2, '45.a. Street Number and Name', 'beneficiaryIntendsStreet');
        $this->addQ($sec2, '45.b. Apt. Ste. Flr.', 'beneficiaryIntendsAptSteFlr');
        $this->addQ($sec2, '45.c. City or Town', 'beneficiaryIntendsCity');
        $this->addQ($sec2, '45.d. State', 'beneficiaryIntendsState');
        $this->addQ($sec2, '45.e. ZIP Code', 'beneficiaryIntendsZip');
        $this->addQ($sec2, '46. Daytime Telephone Number', 'beneficiaryIntendsTelephone');

        $this->addQ($sec2, 'Your Beneficiary\'s Physical Address Abroad', 'headingBeneficiaryPhysicalAddressAbroad', 'heading');
        $this->addQ($sec2, '47.a. Street Number and Name', 'beneficiaryAbroadStreet');
        $this->addQ($sec2, '47.b. Apt. Ste. Flr.', 'beneficiaryAbroadAptSteFlr');
        $this->addQ($sec2, '47.c. City or Town', 'beneficiaryAbroadCity');
        $this->addQ($sec2, '47.d. Province', 'beneficiaryAbroadProvince');
        $this->addQ($sec2, '47.e. Postal Code', 'beneficiaryAbroadPostalCode');
        $this->addQ($sec2, '47.f. Country', 'beneficiaryAbroadCountry');
        $this->addQ($sec2, '48. Daytime Telephone Number', 'beneficiaryAbroadTelephone');

        $this->addQ($sec2, 'Your Beneficiary\'s Name and Address in His or Her Native Alphabet', 'headingBeneficiaryNativeAlphabet', 'heading');
        $this->addQ($sec2, '49.a. Family Name (Last Name)', 'beneficiaryNativeFamilyName');
        $this->addQ($sec2, '49.b. Given Name (First Name)', 'beneficiaryNativeGivenName');
        $this->addQ($sec2, '49.c. Middle Name', 'beneficiaryNativeMiddleName');
        $this->addQ($sec2, '50.a. Street Number and Name', 'beneficiaryNativeStreet');
        $this->addQ($sec2, '50.b. Apt. Ste. Flr.', 'beneficiaryNativeAptSteFlr');
        $this->addQ($sec2, '50.c. City or Town', 'beneficiaryNativeCity');
        $this->addQ($sec2, '50.d. Province', 'beneficiaryNativeProvince');
        $this->addQ($sec2, '50.e. Postal Code', 'beneficiaryNativePostalCode');
        $this->addQ($sec2, '50.f. Country', 'beneficiaryNativeCountry');

        $this->addQ($sec2, '51. Is your fiancé(e) related to you?', 'fianceRelated', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, '52. Provide the nature and degree of relationship (for example, third cousin or maternal uncle).', 'fianceRelationship', 'textarea');
        
        $this->addQ($sec2, '53. Have you and your fiancé(e) met in person during the two years immediately before filing this petition?', 'fianceMetInPerson', 'radio', ['Yes', 'No', 'N/A, beneficiary is my spouse']);
        $this->addQ($sec2, 'If you answered "Yes" to Item Number 53., describe the circumstances of your in-person meeting in Item Number 54. Attach evidence to demonstrate that you were in each other\'s physical presence during the required two year period. If you answered "No," explain your reasons for requesting an exemption from the in person meeting requirement in Item Number 54. and provide evidence that you should be exempt from this requirement. Refer to Part 2., Item Numbers 53. - 54. of the Specific Instructions section of the Instructions for additional information about the requirement to meet. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingInPersonMeetingNote', 'heading');
        $this->addQ($sec2, '54. Describe the circumstances of your in-person meeting or your reasons for requesting an exemption...', 'fianceMeetingCircumstances', 'textarea');

        $this->addQ($sec2, 'International Marriage Broker (IMB) Information', 'headingIMBInformation', 'heading');
        $this->addQ($sec2, '55. Did you meet your beneficiary through the services of an IMB?', 'metThroughIMB', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If you answered "Yes" to Item Number 55., provide the IMB\'s contact information and Website information below. In addition, attach a copy of the signed, written consent form the IMB obtained from your beneficiary authorizing your beneficiary\'s personal contact information to be released to you.', 'headingIMBNote', 'heading');
        $this->addQ($sec2, '56. IMB\'s Name (if any)', 'imbName');
        $this->addQ($sec2, '57.a. Family Name of IMB (Last Name)', 'imbFamilyName');
        $this->addQ($sec2, '57.b. Given Name of IMB (First Name)', 'imbGivenName');
        $this->addQ($sec2, '58. Organization Name of IMB', 'imbOrganizationName');
        $this->addQ($sec2, '59. Website of IMB', 'imbWebsite');
        $this->addQ($sec2, '60.a. Street Number and Name', 'imbStreet');
        $this->addQ($sec2, '60.b. Apt. Ste. Flr.', 'imbAptSteFlr');
        $this->addQ($sec2, '60.c. City or Town', 'imbCity');
        $this->addQ($sec2, '60.d. Province', 'imbProvince');
        $this->addQ($sec2, '60.e. Postal Code', 'imbPostalCode');
        $this->addQ($sec2, '60.f. Country', 'imbCountry');
        $this->addQ($sec2, '61. Daytime Telephone Number', 'imbTelephone');

        $this->addQ($sec2, 'Consular Processing Information', 'headingConsularProcessing', 'heading');
        $this->addQ($sec2, 'Your beneficiary will apply for a visa abroad at the U.S. Embassy or U.S. Consulate at:', 'headingConsularProcessingNote', 'heading');
        $this->addQ($sec2, '62.a. City or Town', 'consulateCity');
        $this->addQ($sec2, '62.b. Country', 'consulateCountry');


        // Part 3. Other Information
        $sec3 = $form->sections()->create(['title' => 'Part 3. Other Information', 'order' => 3]);
        
        $this->addQ($sec3, 'Criminal Information', 'headingCriminalInformation', 'heading');
        $this->addQ($sec3, 'NOTE: These criminal information questions must be answered even if your records were sealed, cleared, or if anyone, including a judge, law enforcement officer, or attorney, told you that you no longer have a record. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingCriminalInformationNote', 'heading');
        $this->addQ($sec3, '1. Have you EVER been subject to a temporary or permanent protection or restraining order (either civil or criminal)?', 'subjectToRestrainingOrder', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, 'Have you EVER been arrested or convicted of any of the following crimes:', 'headingArrestedOrConvicted', 'heading');
        $this->addQ($sec3, '2.a. Domestic violence, sexual assault, child abuse, child neglect, dating violence, elder abuse, stalking or an attempt to commit any of these crimes? (See Part 3. Other Information, Item Numbers 1. - 3.c. of the Instructions for the full definition of the term "domestic violence.")', 'crimeDomesticViolence', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, '2.b. Homicide, murder, manslaughter, rape, abusive sexual contact, sexual exploitation, incest, torture, trafficking, peonage, holding hostage, involuntary servitude, slave trade, kidnapping, abduction, unlawful criminal restraint, false imprisonment, or an attempt to commit any of these crimes?', 'crimeHomicide', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, '2.c. Three or more arrests or convictions, not from a single act, for crimes relating to a controlled substance or alcohol?', 'crimeSubstanceOrAlcohol', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, 'NOTE: If you were ever arrested or convicted of any of the specified crimes, you must submit certified copies of all court and police records showing the charges and disposition for every arrest or conviction. You must do so even if your records were sealed, expunged, or otherwise cleared, and regardless of whether anyone, including a judge, law enforcement officer, or attorney, informed you that you no longer have a criminal record. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingCriminalRecordsNote', 'heading');
        
        $this->addQ($sec3, 'If you have provided information about a conviction for a crime listed in Item Numbers 2.a. - 2.c. and you were being battered or subjected to extreme cruelty at the time of your conviction, select all of the following that apply to you:', 'headingBatteredNote', 'heading');
        $this->addQ($sec3, '3.a. I was acting in self-defense.', 'batteredSelfDefense', 'checkbox', ['Yes']);
        $this->addQ($sec3, '3.b. I violated a protection order issued for my own protection.', 'batteredViolatedOrder', 'checkbox', ['Yes']);
        $this->addQ($sec3, '3.c. I committed, was arrested for, was convicted of, or pled guilty to a crime that did not result in serious bodily injury and there was a connection between the crime and me having been battered or subjected to extreme cruelty.', 'batteredConnection', 'checkbox', ['Yes']);

        $this->addQ($sec3, '4.a. Have you ever been arrested, cited, charged, indicted, convicted, fined, or imprisoned for breaking or violating any law or ordinance in any country, excluding traffic violations (unless a traffic violation was alcohol- or drug-related or involved a fine of $500 or more)?', 'arrestedOrCited', 'radio', ['Yes', 'No']);
        $this->addQ($sec3, 'If the answer to Item Number 4.a. is "Yes," provide information about each of those arrests, citations, charges, indictments, convictions, fines, or imprisonments in the space below. If you were the subject of an order of protection or restraining order and believe you are the victim, please explain those circumstances and provide any evidence to support your claims. Include the dates and outcomes. If you need extra space to complete this section, use the space provided in Part 8. Additional Information.', 'headingArrestsExplanationNote', 'heading');
        $this->addQ($sec3, '4.b. Explanation of arrests, citations, charges...', 'arrestsExplanation', 'textarea');

        $this->addQ($sec3, 'Multiple Filer Waiver Request Information', 'headingMultipleFiler', 'heading');
        $this->addQ($sec3, 'Refer to Part 3. Types of Waivers in the Specific Instructions section of the Instructions for an explanation of the filing waivers. Indicate which one of the following waivers you are requesting:', 'headingMultipleFilerNote', 'heading');
        $this->addQ($sec3, 'Waiver requested (Select only one box):', 'waiverRequested', 'radio', [
            '5.a. Multiple Filer, No Permanent Restraining Orders or Convictions for a Specified Offense (General Waiver)',
            '5.b. Multiple Filer, Prior Permanent Restraining Orders or Criminal Conviction for Specified Offense (Extraordinary Circumstances Waiver)',
            '5.c. Multiple Filer, Prior Permanent Restraining Order or Criminal Convictions for Specified Offense Resulting from Domestic Violence (Mandatory Waiver)',
            '5.d. Not applicable, beneficiary is my spouse or I am not a multiple filer'
        ]);


        // Part 4. Biographic Information
        $sec4 = $form->sections()->create(['title' => 'Part 4. Biographic Information', 'order' => 4]);
        $this->addQ($sec4, '1. Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec4, '2. Race (Select all applicable boxes)', 'race', 'checkbox', ['American Indian or Alaska Native', 'Asian', 'Black or African American', 'Native Hawaiian or Other Pacific Islander', 'White']);
        $this->addQ($sec4, '3. Height: Feet', 'heightFeet', 'number');
        $this->addQ($sec4, '3. Height: Inches', 'heightInches', 'number');
        $this->addQ($sec4, '4. Weight: Pounds', 'weightPounds', 'number');
        $this->addQ($sec4, '5. Eye Color (Select only one box)', 'eyeColor', 'radio', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec4, '6. Hair Color (Select only one box)', 'hairColor', 'radio', ['Bald (No hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);


        // Part 5. Petitioner's Contact Information, Certification, and Signature
        $sec5 = $form->sections()->create(['title' => 'Part 5. Petitioner\'s Contact Information, Certification, and Signature', 'order' => 5]);
        $this->addQ($sec5, 'Petitioner\'s Contact Information', 'headingPetitionerContact', 'heading');
        $this->addQ($sec5, 'Provide your daytime telephone number, mobile telephone number (if any), and email address (if any).', 'headingPetitionerContactNote', 'heading');
        $this->addQ($sec5, '1. Petitioner\'s Daytime Telephone Number', 'petitionerDaytimePhone');
        $this->addQ($sec5, '2. Petitioner\'s Mobile Telephone Number (if any)', 'petitionerMobilePhone');
        $this->addQ($sec5, '3. Petitioner\'s Email Address (if any)', 'petitionerEmailAddress');
        
        $this->addQ($sec5, 'Petitioner\'s Certification and Signature', 'headingPetitionerCertification', 'heading');
        $this->addQ($sec5, 'I certify, under penalty of perjury, that I provided or authorized all of the responses and information contained in and submitted with my petition...', 'headingPetitionerCertText', 'heading');
        $this->addQ($sec5, '4. Petitioner\'s Signature', 'petitionerSignature');
        $this->addQ($sec5, 'Date of Signature (mm/dd/yyyy)', 'petitionerSignatureDate', 'date');


        // Part 6. Interpreter's Contact Information, Certification, and Signature
        $sec6 = $form->sections()->create(['title' => 'Part 6. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 6]);
        $this->addQ($sec6, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec6, '1. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec6, '1. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec6, '2. Interpreter\'s Business or Organization Name', 'interpreterBusiness');
        
        $this->addQ($sec6, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec6, '3. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec6, '4. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobilePhone');
        $this->addQ($sec6, '5. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');
        
        $this->addQ($sec6, 'Interpreter\'s Certification and Signature', 'headingInterpreterCertification', 'heading');
        $this->addQ($sec6, 'I certify, under penalty of perjury, that I am fluent in English and', 'interpreterLanguage');
        $this->addQ($sec6, '6. Interpreter\'s Signature', 'interpreterSignature');
        $this->addQ($sec6, 'Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');


        // Part 7. Contact Information, Declaration, and Signature of the Person Preparing this Petition...
        $sec7 = $form->sections()->create(['title' => 'Part 7. Contact Information, Declaration, and Signature of the Person Preparing this Petition, if Other Than the Petitioner', 'order' => 7]);
        $this->addQ($sec7, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec7, '1. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec7, '1. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec7, '2. Preparer\'s Business or Organization Name', 'preparerBusiness');
        
        $this->addQ($sec7, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec7, '3. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec7, '4. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobilePhone');
        $this->addQ($sec7, '5. Preparer\'s Email Address (if any)', 'preparerEmailAddress');
        
        $this->addQ($sec7, 'Preparer\'s Certification and Signature', 'headingPreparerCertification', 'heading');
        $this->addQ($sec7, '6. Preparer\'s Signature', 'preparerSignature');
        $this->addQ($sec7, 'Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');


        // Part 8. Additional Information
        $sec8 = $form->sections()->create(['title' => 'Part 8. Additional Information', 'order' => 8]);
        $this->addQ($sec8, '1.a Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec8, '1.b. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec8, '1.c. Middle Name', 'additionalMiddleName');
        $this->addQ($sec8, '2. A-Number (if any)', 'additionalANumber');
        
        for ($i = 3; $i <= 7; $i++) {
            $this->addQ($sec8, "{$i}.a. Page Number", "additional{$i}Page");
            $this->addQ($sec8, "{$i}.b. Part Number", "additional{$i}Part");
            $this->addQ($sec8, "{$i}.c. Item Number", "additional{$i}Item");
            $this->addQ($sec8, "{$i}.d. Additional Information", "additional{$i}Info", 'textarea');
        }

        echo "Successfully seeded I-129F form!\n";
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