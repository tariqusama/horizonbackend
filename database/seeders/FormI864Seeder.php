<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI864Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-864%')->orWhere('subtitle', 'like', '%I-864%')->first();
        if (!$service) {
            echo "Service I-864 not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-864'],
            ['name' => 'Affidavit of Support Under Section 213A of the INA', 'description' => 'Form I-864']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Basis For Filing Affidavit of Support
        $sec1 = $form->sections()->create(['title' => 'Part 1. Basis For Filing Affidavit of Support', 'order' => 1]);
        $this->addQ($sec1, 'I am the sponsor submitting this affidavit of support because (Select only one box)', 'basisForFiling', 'radio', [
            '1.a. I am the petitioner. I filed or am filing for the immigration of my relative.',
            '1.b. I filed an alien worker petition on behalf of the intending immigrant...',
            '1.c. I have an ownership interest of at least 5 percent...',
            '1.d. I am the only joint sponsor.',
            '1.e. I am the first or second of two joint sponsors.',
            '1.f. The original petitioner is deceased. I am the substitute sponsor.'
        ]);

        // Part 2. Information About You (Sponsor)
        $sec2 = $form->sections()->create(['title' => 'Part 2. Information About You (Sponsor)', 'order' => 2]);
        $this->addQ($sec2, 'Sponsor\'s Full Legal Name', 'headingSponsorName', 'heading');
        $this->addQ($sec2, '1.a. Family Name (Last Name)', 'sponsorLastName');
        $this->addQ($sec2, '1.b. Given Name (First Name)', 'sponsorFirstName');
        $this->addQ($sec2, '1.c. Middle Name (if applicable)', 'sponsorMiddleName');

        $this->addQ($sec2, 'Sponsor\'s Current Mailing Address', 'headingSponsorMailing', 'heading');
        $this->addQ($sec2, '2.a. In Care Of Name (if any)', 'sponsorMailingInCareOf');
        $this->addQ($sec2, '2.b. Street Number and Name', 'sponsorMailingStreet');
        $this->addQ($sec2, '2.c. Apt. Ste. Flr.', 'sponsorMailingAptSteFlr');
        $this->addQ($sec2, '2.d. City or Town', 'sponsorMailingCity');
        $this->addQ($sec2, '2.e. State', 'sponsorMailingState');
        $this->addQ($sec2, '2.f. ZIP Code', 'sponsorMailingZip');
        $this->addQ($sec2, '2.g. Province', 'sponsorMailingProvince');
        $this->addQ($sec2, '2.h. Postal Code', 'sponsorMailingPostalCode');
        $this->addQ($sec2, '2.i. Country', 'sponsorMailingCountry');
        $this->addQ($sec2, '3. Is your current mailing address the same as your physical address?', 'sponsorMailingSameAsPhysical', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If you answered "No" to Item Number 3., provide your physical address in Item Number 4.', 'headingPhysicalCondition', 'heading');

        $this->addQ($sec2, 'Sponsor\'s Physical Address (if different from the address above)', 'headingSponsorPhysical', 'heading');
        $this->addQ($sec2, '4.a. Street Number and Name', 'sponsorPhysicalStreet');
        $this->addQ($sec2, '4.b. Apt. Ste. Flr.', 'sponsorPhysicalAptSteFlr');
        $this->addQ($sec2, '4.c. City or Town', 'sponsorPhysicalCity');
        $this->addQ($sec2, '4.d. State', 'sponsorPhysicalState');
        $this->addQ($sec2, '4.e. ZIP Code', 'sponsorPhysicalZip');
        $this->addQ($sec2, '4.f. Province', 'sponsorPhysicalProvince');
        $this->addQ($sec2, '4.g. Postal Code', 'sponsorPhysicalPostalCode');
        $this->addQ($sec2, '4.h. Country', 'sponsorPhysicalCountry');

        $this->addQ($sec2, 'Other Information', 'headingSponsorOther', 'heading');
        $this->addQ($sec2, '5. Country of Domicile', 'sponsorCountryOfDomicile');
        $this->addQ($sec2, '6. Date of Birth (mm/dd/yyyy)', 'sponsorDob', 'date');
        $this->addQ($sec2, '7. Country of Birth', 'sponsorCountryOfBirth');
        $this->addQ($sec2, '8. U.S. Social Security Number', 'sponsorSsn');
        
        $this->addQ($sec2, 'Immigration Status', 'headingSponsorImmigration', 'heading');
        $this->addQ($sec2, '9. Immigration Status', 'sponsorImmigrationStatus', 'radio', ['I am a U.S. national.', 'I am a U.S. citizen.', 'I am a lawful permanent resident.']);
        
        $this->addQ($sec2, 'Other Information (continued)', 'headingSponsorOtherInfo', 'heading');
        $this->addQ($sec2, '10. Sponsor\'s A-Number (if any)', 'sponsorANumber');
        $this->addQ($sec2, '11. USCIS Online Account Number (if any)', 'sponsorUscisNumber');
        
        $this->addQ($sec2, 'Military Service (To be completed by petitioner sponsors only.)', 'headingSponsorMilitary', 'heading');
        $this->addQ($sec2, '12. I am currently on active duty in the United States Armed Forces or U.S. Coast Guard.', 'sponsorActiveDuty', 'checkbox', ['Yes']);

        // Part 3. Information About the Principal Immigrant
        $sec3 = $form->sections()->create(['title' => 'Part 3. Information About the Principal Immigrant', 'order' => 3]);
        $this->addQ($sec3, 'Principal Immigrant\'s Full Legal Name', 'headingPrincipalName', 'heading');
        $this->addQ($sec3, '1.a. Family Name (Last Name)', 'principalLastName');
        $this->addQ($sec3, '1.b. Given Name (First Name)', 'principalFirstName');
        $this->addQ($sec3, '1.c. Middle Name (if applicable)', 'principalMiddleName');

        $this->addQ($sec3, 'Current Mailing Address', 'headingPrincipalMailing', 'heading');
        $this->addQ($sec3, '2.a. In Care Of Name (if any)', 'principalMailingInCareOf');
        $this->addQ($sec3, '2.b. Street Number and Name', 'principalMailingStreet');
        $this->addQ($sec3, '2.c. Apt. Ste. Flr.', 'principalMailingAptSteFlr');
        $this->addQ($sec3, '2.d. City or Town', 'principalMailingCity');
        $this->addQ($sec3, '2.e. State', 'principalMailingState');
        $this->addQ($sec3, '2.f. ZIP Code', 'principalMailingZip');
        $this->addQ($sec3, '2.g. Province', 'principalMailingProvince');
        $this->addQ($sec3, '2.h. Postal Code', 'principalMailingPostalCode');
        $this->addQ($sec3, '2.i. Country', 'principalMailingCountry');

        $this->addQ($sec3, 'Other Information', 'headingPrincipalOther', 'heading');
        $this->addQ($sec3, '3. Country of Citizenship or Nationality', 'principalCountryCitizenship');
        $this->addQ($sec3, '4. Date of Birth (mm/dd/yyyy)', 'principalDob', 'date');
        $this->addQ($sec3, '5. Alien Registration Number (A-Number) (if any)', 'principalANumber');
        $this->addQ($sec3, '6. USCIS Online Account Number (if any)', 'principalUscisNumber');
        $this->addQ($sec3, '7. Daytime Telephone Number', 'principalDaytimePhone');

        // Part 4. Information About the Immigrants You Are Sponsoring
        $sec4 = $form->sections()->create(['title' => 'Part 4. Information About the Immigrants You Are Sponsoring', 'order' => 4]);
        $this->addQ($sec4, '1. I am sponsoring the principal immigrant named in Part 3.', 'sponsoringPrincipal', 'radio', ['Yes', 'No, I am sponsoring family members in Part 4. as the second joint sponsor or I am sponsoring family members who are immigrating more than six months after the principal immigrant.']);
        $this->addQ($sec4, '2. I am sponsoring the following family members immigrating at the same time or within six months of the principal immigrant named in Part 3.', 'sponsoringWithin6Months', 'checkbox', ['Yes']);
        $this->addQ($sec4, '3. I am sponsoring the following family members who are immigrating more than six months after the principal immigrant.', 'sponsoringAfter6Months', 'checkbox', ['Yes']);

        for ($i = 4; $i <= 7; $i++) {
            $fm = $i - 3;
            $this->addQ($sec4, "Family Member {$fm}", "headingFamilyMember{$fm}", 'heading');
            $this->addQ($sec4, "{$i}.a. Family Name (Last Name)", "fm{$fm}LastName");
            $this->addQ($sec4, "{$i}.b. Given Name (First Name)", "fm{$fm}FirstName");
            $this->addQ($sec4, "{$i}.c. Middle Name", "fm{$fm}MiddleName");
            $this->addQ($sec4, "{$i}.d. Relationship to Principal Immigrant", "fm{$fm}Relationship");
            $this->addQ($sec4, "{$i}.e. Date of Birth (mm/dd/yyyy)", "fm{$fm}Dob", 'date');
            $this->addQ($sec4, "{$i}.f. Alien Registration Number (A-Number)", "fm{$fm}ANumber");
            $this->addQ($sec4, "{$i}.g. USCIS Online Account Number", "fm{$fm}UscisNumber");
        }

        // Part 5. Sponsor's Household Size
        $sec5 = $form->sections()->create(['title' => 'Part 5. Sponsor\'s Household Size', 'order' => 5]);
        $this->addQ($sec5, '1. Enter the total number of immigrants you are sponsoring on this affidavit...', 'householdSponsoringCount', 'number');
        $this->addQ($sec5, '2. Yourself.', 'householdYourselfCount', 'number');
        $this->addQ($sec5, '3. If you are currently married, enter "1" for your spouse.', 'householdSpouseCount', 'number');
        $this->addQ($sec5, '4. If you have dependent children, enter the number here.', 'householdChildrenCount', 'number');
        $this->addQ($sec5, '5. If you have any other dependents, enter the number here.', 'householdOtherDependentsCount', 'number');
        $this->addQ($sec5, '6. If you have sponsored any other persons on Form I-864 or Form I-864EZ... enter the number here.', 'householdPreviouslySponsoredCount', 'number');
        $this->addQ($sec5, '7. If you have siblings, parents, or adult children... combining their income with yours by submitting Form I-864A...', 'householdI864aCount', 'number');
        $this->addQ($sec5, '8. Add together Part 5., Item Numbers 1. - 7. and enter the number here.', 'householdTotalSize', 'number');

        // Part 6. Sponsor's Employment and Income
        $sec6 = $form->sections()->create(['title' => 'Part 6. Sponsor\'s Employment and Income', 'order' => 6]);
        $this->addQ($sec6, 'I am currently:', 'headingEmployment', 'heading');
        $this->addQ($sec6, '1. Employed as a/an', 'employedAs');
        $this->addQ($sec6, '2. Name of Employer 1', 'employer1Name');
        $this->addQ($sec6, '3. Name of Employer 2 (if applicable)', 'employer2Name');
        $this->addQ($sec6, '4. Self-Employed as a/an (Occupation)', 'selfEmployedAs');
        $this->addQ($sec6, '5. Retired Since (mm/dd/yyyy)', 'retiredSince', 'date');
        $this->addQ($sec6, '6. Unemployed Since (mm/dd/yyyy)', 'unemployedSince', 'date');
        $this->addQ($sec6, '7. My current individual annual income is: $', 'individualAnnualIncome', 'number');

        $this->addQ($sec6, 'Income you are using from any other person who was counted in your household size...', 'headingOtherIncome', 'heading');
        for ($i = 8; $i <= 11; $i++) {
            $p = $i - 7;
            $this->addQ($sec6, "Person {$p}", "headingPerson{$p}", 'heading');
            $this->addQ($sec6, "{$i}.a. Name", "person{$p}Name");
            $this->addQ($sec6, "{$i}.b. Relationship", "person{$p}Relationship");
            $this->addQ($sec6, "{$i}.c. Current Income $", "person{$p}Income", 'number');
        }

        $this->addQ($sec6, '12. My Current Annual Household Income $', 'householdTotalIncome', 'number');
        $this->addQ($sec6, '13. The people listed in Item Numbers 8. - 11. have completed Form I-864A.', 'personsCompletedI864A', 'checkbox', ['Yes']);
        $this->addQ($sec6, '14. One or more of the people listed... do not need to complete Form I-864A because he or she is the intending immigrant...', 'personsNotNeedingI864A', 'checkbox', ['Yes']);

        $this->addQ($sec6, 'Federal Tax Return Information', 'headingTaxReturn', 'heading');
        $this->addQ($sec6, '15. Have you filed a Federal income tax return for each of the three most recent tax years?', 'filedTax3Years', 'radio', ['Yes', 'No']);
        $this->addQ($sec6, '16.a. Most Recent Tax Year', 'taxYear1');
        $this->addQ($sec6, '16.a. Total Income $', 'taxIncome1', 'number');
        $this->addQ($sec6, '16.b. 2nd Most Recent Tax Year', 'taxYear2');
        $this->addQ($sec6, '16.b. Total Income $', 'taxIncome2', 'number');
        $this->addQ($sec6, '16.c. 3rd Most Recent Tax Year', 'taxYear3');
        $this->addQ($sec6, '16.c. Total Income $', 'taxIncome3', 'number');
        $this->addQ($sec6, '17. I was not required to file a Federal income tax return as my income was below the IRS required level...', 'notRequiredToFileTax', 'checkbox', ['Yes']);

        // Part 7. Use of Assets to Supplement Income
        $sec7 = $form->sections()->create(['title' => 'Part 7. Use of Assets to Supplement Income (if Applicable)', 'order' => 7]);
        $this->addQ($sec7, 'Your Assets (if applicable)', 'headingYourAssets', 'heading');
        $this->addQ($sec7, '1. Enter the balance of all cash, savings, and checking accounts. $', 'assetBalance', 'number');
        $this->addQ($sec7, '2. Enter the net cash value of real-estate holdings. $', 'assetRealEstate', 'number');
        $this->addQ($sec7, '3. Enter the net cash value of all stocks, bonds, certificates of deposit... $', 'assetStocksBonds', 'number');
        $this->addQ($sec7, '4. Add together Item Numbers 1. - 3. and enter the number here. $', 'assetTotalSponsor', 'number');

        $this->addQ($sec7, 'Assets of your household members (if applicable)', 'headingHouseholdAssets', 'heading');
        $this->addQ($sec7, '5. Add together the household members\' assets reported on all the Form I-864A... $', 'assetTotalHousehold', 'number');

        $this->addQ($sec7, 'Assets of the principal sponsored immigrant (if applicable)', 'headingPrincipalAssets', 'heading');
        $this->addQ($sec7, '6. Enter the balance of the principal immigrant\'s savings and checking accounts. $', 'assetPrincipalBalance', 'number');
        $this->addQ($sec7, '7. Enter the net cash value of all the principal immigrant\'s real estate holdings. $', 'assetPrincipalRealEstate', 'number');
        $this->addQ($sec7, '8. Enter the current cash value of the principal immigrant\'s stocks, bonds, certificates of deposit... $', 'assetPrincipalStocksBonds', 'number');
        $this->addQ($sec7, '9. Add together Item Numbers 6. - 8. and enter the number here. $', 'assetPrincipalTotal', 'number');
        
        $this->addQ($sec7, 'Total Value of Assets', 'headingTotalAssets', 'heading');
        $this->addQ($sec7, '10. Add together Item Numbers 4., 5., and 9. and enter the number here. $', 'assetGrandTotal', 'number');

        // Part 8. Sponsor's Contract, Contact Information, Certification, and Signature
        $sec8 = $form->sections()->create(['title' => 'Part 8. Sponsor\'s Contract, Contact Information, Certification, and Signature', 'order' => 8]);
        $this->addQ($sec8, 'Sponsor\'s Statement Regarding the Interpreter', 'headingSponsorStatement', 'heading');
        $this->addQ($sec8, '1.A. I can read and understand English...', 'sponsorStatementEnglish', 'checkbox', ['Yes']);
        $this->addQ($sec8, '1.B. The interpreter named in Part 9. read to me every question... in language:', 'sponsorStatementLanguage');
        $this->addQ($sec8, '2. At my request, the preparer named in Part 10., prepared this affidavit for me...', 'sponsorStatementPreparer', 'checkbox', ['Yes']);

        $this->addQ($sec8, 'Sponsor\'s Contact Information', 'headingSponsorContact', 'heading');
        $this->addQ($sec8, '3. Sponsor\'s Daytime Telephone Number', 'sponsorDaytimePhone');
        $this->addQ($sec8, '4. Sponsor\'s Mobile Telephone Number (if any)', 'sponsorMobilePhone');
        $this->addQ($sec8, '5. Sponsor\'s Email Address (if any)', 'sponsorEmailAddress');

        $this->addQ($sec8, 'Sponsor\'s Declaration and Certification', 'headingSponsorCertification', 'heading');
        $this->addQ($sec8, 'I certify, under penalty of perjury, that all of the information in my affidavit and any document submitted with it were provided or authorized by me...', 'headingSponsorCertText', 'heading');
        $this->addQ($sec8, '6. Sponsor\'s Signature', 'sponsorSignature');
        $this->addQ($sec8, '6. Date of Signature (mm/dd/yyyy)', 'sponsorSignatureDate', 'date');

        // Part 9. Interpreter's Contact Information, Certification, and Signature
        $sec9 = $form->sections()->create(['title' => 'Part 9. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 9]);
        $this->addQ($sec9, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec9, '1. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec9, '1. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec9, '2. Interpreter\'s Business or Organization Name', 'interpreterBusiness');
        
        $this->addQ($sec9, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec9, '3. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec9, '4. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobilePhone');
        $this->addQ($sec9, '5. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');
        
        $this->addQ($sec9, 'Interpreter\'s Certification and Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec9, 'I certify, under penalty of perjury, that: I am fluent in English and:', 'interpreterLanguage');
        $this->addQ($sec9, '6. Interpreter\'s Signature', 'interpreterSignature');
        $this->addQ($sec9, '6. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');

        // Part 10. Contact Information, Declaration, and Signature of the Person Preparing this Affidavit
        $sec10 = $form->sections()->create(['title' => 'Part 10. Contact Information, Declaration, and Signature of the Person Preparing this Affidavit', 'order' => 10]);
        $this->addQ($sec10, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec10, '1. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec10, '1. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec10, '2. Preparer\'s Business or Organization Name', 'preparerBusiness');
        
        $this->addQ($sec10, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec10, '3. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec10, '4. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobilePhone');
        $this->addQ($sec10, '5. Preparer\'s Email Address (if any)', 'preparerEmailAddress');
        
        $this->addQ($sec10, 'Preparer\'s Certification and Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec10, '6. Preparer\'s Signature', 'preparerSignature');
        $this->addQ($sec10, '6. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');

        // Part 11. Additional Information
        $sec11 = $form->sections()->create(['title' => 'Part 11. Additional Information', 'order' => 11]);
        $this->addQ($sec11, '1.a Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec11, '1.b. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec11, '1.c. Middle Name', 'additionalMiddleName');
        $this->addQ($sec11, '2. A-Number (if any)', 'additionalANumber');
        
        for ($i = 3; $i <= 6; $i++) {
            $this->addQ($sec11, "{$i}.a. Page Number", "additional{$i}Page");
            $this->addQ($sec11, "{$i}.b. Part Number", "additional{$i}Part");
            $this->addQ($sec11, "{$i}.c. Item Number", "additional{$i}Item");
            $this->addQ($sec11, "{$i}.d. Additional Information", "additional{$i}Info", 'textarea');
        }

        echo "Successfully seeded I-864 form!\n";
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