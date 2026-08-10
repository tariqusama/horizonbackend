<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormI485Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%I-485%')->orWhere('subtitle', 'like', '%I-485%')->first();
        if (!$service) {
            echo "Service I-485 not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'i-485'],
            ['name' => 'Application to Register Permanent Residence or Adjust Status', 'description' => 'Form I-485']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Intro
        $secIntro = $form->sections()->create(['title' => 'Form Details', 'order' => 1]);
        $this->addQ($secIntro, 'NOTE TO ALL APPLICANTS: If you do not completely fill out this application or fail to submit required documents listed in the Instructions, U.S. Citizenship and Immigration Services (USCIS) may reject or deny your application.', 'headingIntroNote', 'heading');

        // Part 1. Information About You
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About You (Person applying for lawful permanent residence)', 'order' => 2]);
        $this->addQ($sec1, 'For all sections of this application, if you need to provide any additional information or are instructed to provide an explanation, use the space provided in Part 14. Additional Information.', 'headingPart1Note', 'heading');
        
        $this->addQ($sec1, '1. Your Current Legal Name (Do not provide a nickname)', 'headingCurrentName', 'heading');
        $this->addQ($sec1, 'Family Name (Last Name)', 'lastName');
        $this->addQ($sec1, 'Given Name (First Name)', 'firstName');
        $this->addQ($sec1, 'Middle Name (if applicable)', 'middleName');

        $this->addQ($sec1, 'Provide all other names you have ever used, including your family name at birth, other legal names, nicknames, aliases, and assumed names.', 'headingOtherNamesNote', 'heading');
        $this->addQ($sec1, '2. Other Names You Have Used Since Birth (if applicable)', 'headingOtherNames', 'heading');
        $this->addQ($sec1, 'Family Name (Last Name)', 'otherLastName');
        $this->addQ($sec1, 'Given Name (First Name)', 'otherFirstName');
        $this->addQ($sec1, 'Middle Name (if applicable)', 'otherMiddleName');

        $this->addQ($sec1, '3. Have you ever used any other date of birth?', 'usedOtherDob', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes," provide all other dates of birth (mm/dd/yyyy).', 'headingOtherDobNote', 'heading');
        $this->addQ($sec1, 'Date of Birth (mm/dd/yyyy)', 'otherDob', 'date');

        $this->addQ($sec1, '4. Do you have an Alien Registration Number (A-Number)?', 'hasANumber', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes," provide your A-Number.', 'headingANumberNote', 'heading');
        $this->addQ($sec1, 'A-Number', 'aNumber');

        $this->addQ($sec1, '5. Have you ever used, or been assigned, any other A-Number?', 'hasOtherANumber', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes," provide the A-Numbers.', 'headingOtherANumberNote', 'heading');
        $this->addQ($sec1, 'Other A-Number', 'otherANumber');

        $this->addQ($sec1, '6. Sex', 'sex', 'radio', ['Male', 'Female']);
        $this->addQ($sec1, '7. Place of Birth: City or Town of Birth', 'cityOfBirth');
        $this->addQ($sec1, '7. Place of Birth: Country of Birth', 'countryOfBirth');
        $this->addQ($sec1, '8. Country of Citizenship or Nationality', 'countryOfCitizenship');
        $this->addQ($sec1, '9. USCIS Online Account Number (if any)', 'uscisAccountNumber');
        $this->addQ($sec1, 'If one has been assigned, you can find it on a notice that USCIS may have sent to you.', 'headingUscisNote', 'heading');

        $this->addQ($sec1, '10. Recent Immigration History', 'headingImmigrationHistory', 'heading');
        $this->addQ($sec1, 'If you last entered the United States using a passport or travel document, provide the following information.', 'headingPassportNote', 'heading');
        $this->addQ($sec1, 'Passport or Travel Document Number Used at Last Arrival', 'passportNumber');
        $this->addQ($sec1, 'Expiration Date of this Passport or Travel Document (mm/dd/yyyy)', 'passportExpiry', 'date');
        $this->addQ($sec1, 'Country that Issued this Passport or Travel Document', 'passportCountry');
        $this->addQ($sec1, 'Nonimmigrant Visa Number Used During Most Recent Arrival (if any)', 'visaNumber');
        $this->addQ($sec1, 'Date Nonimmigrant Visa Was Issued (mm/dd/yyyy)', 'visaIssueDate', 'date');
        
        $this->addQ($sec1, 'Place and Date of Last Arrival into the United States', 'headingArrival', 'heading');
        $this->addQ($sec1, 'City or Town', 'arrivalCity');
        $this->addQ($sec1, 'State', 'arrivalState');
        $this->addQ($sec1, 'Date of Last Arrival (mm/dd/yyyy)', 'arrivalDate', 'date');

        $this->addQ($sec1, '11. When I last arrived in the United States:', 'headingWhenArrived', 'heading');
        $this->addQ($sec1, 'I was inspected at a Port of Entry and admitted as (for example, exchange visitor, visitor, temporary worker, student):', 'admittedAs');
        $this->addQ($sec1, 'I was inspected at a Port of Entry and paroled as (for example, humanitarian parole, Cuban parole):', 'paroledAs');
        $this->addQ($sec1, 'I came into the United States without admission or parole.', 'withoutAdmission', 'checkbox', ['Yes']);
        $this->addQ($sec1, 'Other:', 'otherArrivalDetails');

        $this->addQ($sec1, '12. If you were issued a Form I-94 Arrival/Departure Record, provide the information from your most recent Form I-94 below:', 'headingI94Note', 'heading');
        $this->addQ($sec1, 'Form I-94 Arrival/Departure Record Number', 'i94Number');
        $this->addQ($sec1, 'Expiration Date of Authorized Stay Shown on Form I-94 (mm/dd/yyyy) or Type or Print "D/S" for Duration of Status', 'i94Expiry');
        $this->addQ($sec1, 'Immigration Status on Form I-94 (for example, class of admission, or paroled, if paroled)', 'i94Status');

        $this->addQ($sec1, '13. Was your last arrival the first time you were physically present in the United States?', 'firstTimeInUs', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, '14. What is your current immigration status (if it has changed since your last arrival)?', 'currentImmigrationStatus');
        $this->addQ($sec1, '14. Expiration Date of Current Immigration Status (mm/dd/yyyy) or Type or Print "D/S" for Duration of Status', 'currentStatusExpiry');
        $this->addQ($sec1, '15. Have you ever been issued an "alien crewman" visa?', 'issuedCrewmanVisa', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, '16. Did you last arrive in the United States to join a vessel as a seaman or crewman, or while serving in any capacity aboard a vessel or aircraft?', 'arrivedAsCrewman', 'radio', ['Yes', 'No']);

        $this->addQ($sec1, '17. Addresses: Current U.S. Physical Address', 'headingCurrentAddress', 'heading');
        $this->addQ($sec1, 'In Care Of Name (if any)', 'physicalInCareOf');
        $this->addQ($sec1, 'Street Number and Name', 'physicalStreet');
        $this->addQ($sec1, 'Apt. Ste. Flr. Number', 'physicalAptSteFlr');
        $this->addQ($sec1, 'City or Town', 'physicalCity');
        $this->addQ($sec1, 'State', 'physicalState');
        $this->addQ($sec1, 'ZIP Code', 'physicalZip');
        $this->addQ($sec1, 'Date You First Resided at This Address (mm/dd/yyyy)', 'physicalDateFrom', 'date');

        $this->addQ($sec1, '18. Is this your current mailing address?', 'physicalSameAsMailing', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "No," provide your current mailing address.', 'headingMailingNote', 'heading');
        
        $this->addQ($sec1, 'Current Mailing Address (Safe or Alternate Mailing Address, if applicable)', 'headingMailingAddress', 'heading');
        $this->addQ($sec1, 'In Care Of Name (if any)', 'mailingInCareOf');
        $this->addQ($sec1, 'Street Number and Name', 'mailingStreet');
        $this->addQ($sec1, 'Apt. Ste. Flr. Number', 'mailingAptSteFlr');
        $this->addQ($sec1, 'City or Town', 'mailingCity');
        $this->addQ($sec1, 'State', 'mailingState');
        $this->addQ($sec1, 'ZIP Code', 'mailingZip');

        $this->addQ($sec1, 'Have you resided at your current address for at least 5 years?', 'resided5Years', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "No," provide your prior address(es) for the last 5 years. Use the space provided in Part 14. Additional Information, if necessary.', 'headingPriorAddressNote', 'heading');
        
        $this->addQ($sec1, 'Prior Address', 'headingPriorAddress', 'heading');
        $this->addQ($sec1, 'In Care Of Name (if any)', 'priorInCareOf');
        $this->addQ($sec1, 'Street Number and Name', 'priorStreet');
        $this->addQ($sec1, 'Apt. Ste. Flr. Number', 'priorAptSteFlr');
        $this->addQ($sec1, 'City or Town', 'priorCity');
        $this->addQ($sec1, 'State', 'priorState');
        $this->addQ($sec1, 'ZIP Code', 'priorZip');
        $this->addQ($sec1, 'Province', 'priorProvince');
        $this->addQ($sec1, 'Postal Code', 'priorPostalCode');
        $this->addQ($sec1, 'Country', 'priorCountry');
        $this->addQ($sec1, 'Dates of Residence: From (mm/dd/yyyy)', 'priorDateFrom', 'date');
        $this->addQ($sec1, 'Dates of Residence: To (mm/dd/yyyy)', 'priorDateTo', 'date');

        $this->addQ($sec1, 'Provide your most recent physical address outside the United States where you lived for more than one year (if not already listed above).', 'headingRecentOutsideUsNote', 'heading');
        $this->addQ($sec1, 'Most Recent Address Outside the United States', 'headingRecentOutsideAddress', 'heading');
        $this->addQ($sec1, 'Street Number and Name', 'outsideStreet');
        $this->addQ($sec1, 'Apt. Ste. Flr. Number', 'outsideAptSteFlr');
        $this->addQ($sec1, 'City or Town', 'outsideCity');
        $this->addQ($sec1, 'State', 'outsideState');
        $this->addQ($sec1, 'ZIP Code', 'outsideZip');
        $this->addQ($sec1, 'Province', 'outsideProvince');
        $this->addQ($sec1, 'Postal Code', 'outsidePostalCode');
        $this->addQ($sec1, 'Country', 'outsideCountry');
        $this->addQ($sec1, 'Dates of Residence: From (mm/dd/yyyy)', 'outsideDateFrom', 'date');
        $this->addQ($sec1, 'Dates of Residence: To (mm/dd/yyyy)', 'outsideDateTo', 'date');

        $this->addQ($sec1, '19. Social Security Card', 'headingSocialSecurity', 'heading');
        $this->addQ($sec1, 'Has the Social Security Administration (SSA) ever officially issued a Social Security card to you?', 'issuedSsnCard', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes," provide your U.S. Social Security Number (SSN).', 'headingSsnNote', 'heading');
        $this->addQ($sec1, 'U.S. Social Security Number', 'ssn');
        $this->addQ($sec1, 'Do you want the SSA to issue you a Social Security card?', 'wantSsaCard', 'radio', ['Yes', 'No']);
        $this->addQ($sec1, 'If you answered "Yes," you must also answer "Yes" to the Consent for Disclosure below.', 'headingConsentNote', 'heading');
        $this->addQ($sec1, 'Consent for Disclosure: I authorize disclosure of information from this application to the SSA as required for the purpose of assigning me an SSN and issuing me a Social Security Card.', 'ssaConsent', 'radio', ['Yes', 'No']);

        // Part 2. Application Type or Filing Category
        $sec2 = $form->sections()->create(['title' => 'Part 2. Application Type or Filing Category', 'order' => 3]);
        $this->addQ($sec2, '1. Are you filing for adjustment of status with the Executive Office for Immigration Review (EOIR) while in removal, exclusion, rescission, or deportation proceedings?', 'filingWithEoir', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec2, '2. Receipt Number of Underlying Petition (if any)', 'underlyingReceiptNumber');
        $this->addQ($sec2, 'Priority Date from Underlying Petition (if any) (mm/dd/yyyy)', 'underlyingPriorityDate', 'date');

        $this->addQ($sec2, 'I am filing this Form I-485 as a (select only one box):', 'filingAs', 'radio', [
            'Principal Applicant',
            'Derivative Applicant (Provide the following information about the principal applicant.)'
        ]);
        $this->addQ($sec2, 'Principal Applicant\'s Name: Family Name (Last Name)', 'principalLastName');
        $this->addQ($sec2, 'Principal Applicant\'s Name: Given Name (First Name)', 'principalFirstName');
        $this->addQ($sec2, 'Principal Applicant\'s Name: Middle Name (if applicable)', 'principalMiddleName');
        $this->addQ($sec2, 'Principal Applicant\'s A-Number (if any)', 'principalANumber');
        $this->addQ($sec2, 'Principal Applicant\'s Date of Birth (mm/dd/yyyy)', 'principalDob', 'date');

        $this->addQ($sec2, 'I am applying based on the following category (You must select ONLY ONE category. If you are filing as a derivative applicant, select the appropriate box based on the category under which the principal applicant is applying or has applied. See the Form I-485 Instructions for more information, including any Additional Instructions that relate to the immigrant category you select.):', 'headingCategoryInstructions', 'heading');
        
        $this->addQ($sec2, '3.a. Family-based', 'headingFamilyBased', 'heading');
        $this->addQ($sec2, 'Immediate relative of a U.S. citizen, Form I-130, I-129F, or I-360 (select your specific category below):', 'immediateRelativeOfUsCitizen', 'radio', [
            'Spouse of a U.S. Citizen.',
            'Unmarried child under 21 years of age of a U.S. citizen.',
            'Parent of a U.S. citizen (if the citizen is at least 21 years of age).',
            'Person admitted to the United States as a fiancé(e) or child of a fiancé(e) of a U.S. citizen (K-1/K-2 Nonimmigrant).',
            'Widow or widower of a U.S. citizen.',
            'Spouse, child, or parent of a deceased U.S. active-duty service member in the armed forces under the National Defense Authorization Act (NDAA).'
        ]);
        
        $this->addQ($sec2, 'Other relative of a U.S. citizen under the family-based preference categories, Form I-130 (select your specific category below):', 'otherRelativeOfUsCitizen', 'radio', [
            'Unmarried son or daughter of a U.S. citizen and I am 21 years of age or older.',
            'Married son or daughter of a U.S. citizen.',
            'Brother or sister of a U.S. citizen (if the citizen is at least 21 years of age).'
        ]);
        
        $this->addQ($sec2, 'Relative of a lawful permanent resident under the family-based preference categories, Form I-130 (select your specific category below):', 'relativeOfLpr', 'radio', [
            'Spouse of a lawful permanent resident.',
            'Unmarried child under 21 years of age of a lawful permanent resident.',
            'Unmarried son or daughter of a lawful permanent resident and I am 21 years of age or older.'
        ]);
        
        $this->addQ($sec2, 'VAWA self-petitioner (victim of battery or extreme cruelty), Form I-360 (select your specific category below):', 'vawaSelfPetitioner', 'radio', [
            'VAWA self-petitioning spouse of a U.S. citizen or lawful permanent resident.',
            'VAWA self-petitioning child of a U.S. citizen or lawful permanent resident.',
            'VAWA self-petitioning parent of a U.S. citizen (if the citizen is at least 21 years of age).'
        ]);
        
        $this->addQ($sec2, '3.b. Employment-based', 'headingEmploymentBased', 'heading');
        $this->addQ($sec2, 'Alien Workers, Form I-140 (select your category below and answer the following questions below, as applicable):', 'alienWorkersI140', 'radio', [
            'Alien Investor, Form I-526 or Form I-526E',
            'Alien of Extraordinary Ability',
            'Outstanding Professor or Researcher',
            'Multinational Executive or Manager',
            'Member of the Professions Holding an Advanced Degree or Alien of Exceptional Ability (who is NOT seeking a National Interest Waiver)',
            'A Professional (at a minimum, requiring a bachelor\'s degree or a foreign degree equivalent to a U.S. bachelor\'s degree)',
            'A Skilled Worker (requiring at least 2 years of specialized training or experience)',
            'Any Other Worker (requiring less than 2 years of training or experience)',
            'An Alien Applying For a National Interest Waiver (who IS a member of the professions holding an advanced degree or an alien of exceptional ability)'
        ]);

        $this->addQ($sec2, '3.c. Special Immigrant', 'headingSpecialImmigrant', 'heading');
        $this->addQ($sec2, 'Special Immigrant category (select only one):', 'specialImmigrantCategory', 'radio', [
            'Special Immigrant Juvenile, Form I-360',
            'Certain Afghan or Iraqi National, Form I-360 or Form DS-157',
            'Certain International Broadcaster, Form I-360',
            'Certain G-4 International Organization or Family Member or NATO-6 Employee or Family Member, Form I-360',
            'Certain U.S. Armed Forces Members (also known as the Six and Six program), Form I-360',
            'Panama Canal Zone Employees, Form I-360',
            'Certain Physicians, Form I-360',
            'Certain Employee or Former Employee of the U.S. Government Abroad, DS-1884'
        ]);
        
        $this->addQ($sec2, 'Religious Worker, Form I-360 (select your specific category below):', 'religiousWorkerCategory', 'radio', [
            'Minister of Religion',
            'Other Religious Worker'
        ]);

        $this->addQ($sec2, '3.d. Asylee or Refugee', 'headingAsyleeRefugee', 'heading');
        $this->addQ($sec2, 'Asylum Status (Immigration and Nationality Act (INA) section 208), Form I-589 or Form I-730', 'asylumStatus', 'checkbox', ['Yes']);
        $this->addQ($sec2, 'Refugee Status (INA section 207), Form I-590 or Form I-730', 'refugeeStatus', 'checkbox', ['Yes']);

        $this->addQ($sec2, '3.e. Human Trafficking Victim or Crime Victim', 'headingTraffickingVictim', 'heading');
        $this->addQ($sec2, 'Human Trafficking Victim or Crime Victim (select only one):', 'traffickingVictimCategory', 'radio', [
            'Human Trafficking Victim (T Nonimmigrant), Form I-914 or Derivative Family Member, Form I-914A',
            'Victim of Qualifying Criminal Activity (U Nonimmigrant), Form I-918, Derivative Family Member, Form I-918A, or Qualifying Family Member, Form I-929'
        ]);

        $this->addQ($sec2, '3.f. Special Programs Based on Certain Public Laws', 'headingSpecialPrograms', 'heading');
        $this->addQ($sec2, 'Special Programs category (select only one):', 'specialProgramsCategory', 'radio', [
            'The Cuban Adjustment Act',
            'A Victim of Battery or Extreme Cruelty as a Spouse or Child Under the Cuban Adjustment Act',
            'Applicant Adjusting Based on Dependent Status Under the Haitian Refugee Immigrant Fairness Act',
            'A Victim of Battery or Extreme Cruelty as a Spouse or Child Applying Based on Dependent Status Under the Haitian Refugee Immigrant Fairness Act',
            'Lautenberg Parolees',
            'Diplomats or High-Ranking Officials Unable to Return Home (Section 13 of the Act of September 11, 1957)',
            'Nationals of Vietnam, Cambodia, and Laos Applying for Adjustment of Status Under section 586 of Public Law 106-429',
            'Applicant Adjusting Under the Amerasian Act (October 22, 1982), Form I-360'
        ]);

        $this->addQ($sec2, '3.g. Additional Options', 'headingAdditionalOptions', 'heading');
        $this->addQ($sec2, 'Additional Options category (select only one):', 'additionalOptionsCategory', 'radio', [
            'Diversity Visa program',
            'Continuous Residence in the United States Since Before January 1, 1972 ("Registry")',
            'Individual Born in the United States Under Diplomatic Status',
            'S Nonimmigrants and Qualifying Family Members (can only adjust in this category with an approved Form I-854B filed by a law enforcement officer)',
            'Other Eligibility'
        ]);
        $this->addQ($sec2, 'Other Eligibility', 'otherEligibilityText');
        
        $this->addQ($sec2, 'Did a relative file the associated Form I-140 for you (or for the principal applicant if you are a derivative applicant) or does a relative have a significant ownership interest (5 percent or more) in the business that filed Form I-140 for you?', 'relativeFiledI140', 'radio', ['Yes', 'No', 'N/A (I am adjusting on the basis of a Form I-140 self-petition)']);
        $this->addQ($sec2, 'If you answered "Yes," is this relative your (select only one box):', 'headingRelativeTypeNote', 'heading');
        $this->addQ($sec2, 'Relative Type', 'relativeType', 'radio', ['Mother', 'Father', 'Adult Son', 'Adult Daughter', 'Child', 'Brother', 'Sister', 'None of These']);
        $this->addQ($sec2, 'Is the relative above a:', 'relativeStatus', 'radio', ['U.S. Citizen', 'U.S. National', 'Lawful Permanent Resident', 'None of These']);
        
        $this->addQ($sec2, 'If you selected Asylee or Refugee:', 'headingAsyleeRefugeeNote', 'heading');
        $this->addQ($sec2, 'Date you were granted asylum (mm/dd/yyyy)', 'asylumDate', 'date');
        $this->addQ($sec2, 'Date of initial admission as refugee (mm/dd/yyyy)', 'refugeeDate', 'date');
        $this->addQ($sec2, 'If you selected Diversity Visa program, provide your Diversity Visa Rank Number:', 'headingDiversityNote', 'heading');
        $this->addQ($sec2, 'Diversity Visa Rank Number', 'diversityRankNumber');
        
        $this->addQ($sec2, '4. If you selected a family-based, employment-based, special immigrant, or Diversity Visa immigrant category listed above in Item Numbers 3.a. - 3.g. as the basis for your application for adjustment of status, are you applying for adjustment based on INA section 245(i)?', 'applyingUnder245i', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, '5. Are you 21 years of age or older and applying for adjustment based on classification as a child, under the provisions of the Child Status Protection Act (CSPA)?', 'applyingUnderCspa', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: For more information to determine if you are eligible under CSPA, see the Who May File Form I-485 section of these Instructions.', 'headingCspaNote', 'heading');

        // Part 3. Request for Exemption for Intending Immigrant's Affidavit of Support
        $sec3 = $form->sections()->create(['title' => 'Part 3. Request for Exemption for Intending Immigrant\'s Affidavit of Support Under Section 213A of the INA', 'order' => 4]);
        $this->addQ($sec3, 'I am requesting an exemption from submitting an Affidavit of Support Under Section 213A of the INA (Form I-864 or Form I-864EZ) because (select only one):', 'affidavitExemption', 'radio', [
            '1.a. I have earned or can receive credit for 40 qualifying quarters (credits) of work in the United States...',
            '1.b. I am under 18 years of age, unmarried, the child of a U.S. citizen, am not likely to become a public charge...',
            '1.c. I am applying under the widow or widower of a U.S. citizen (Form I-360) immigrant category.',
            '1.d. I am applying as a VAWA self-petitioner.',
            '1.e. None of these exemptions apply to me and I am not required by statute to submit an Affidavit of Support...',
            '1.f. None of these exemptions apply to me and I am not requesting an exemption as I am required to submit an Affidavit of Support...'
        ]);

        // Part 4. Additional Information About You
        $sec4 = $form->sections()->create(['title' => 'Part 4. Additional Information About You', 'order' => 5]);
        $this->addQ($sec4, '1. Have you ever applied for an immigrant visa to obtain permanent resident status at a U.S. Embassy or U.S. Consulate abroad?', 'appliedForVisaAbroad', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, 'If you answered "Yes," complete Item Numbers 2. - 4. below.', 'headingVisaAbroadNote', 'heading');
        $this->addQ($sec4, '2. Location of U.S. Embassy or U.S. Consulate: City or Town', 'embassyCity');
        $this->addQ($sec4, '2. Location of U.S. Embassy or U.S. Consulate: Country', 'embassyCountry');
        $this->addQ($sec4, '3. Decision (for example, approved, refused, denied, withdrawn)', 'visaDecision');
        $this->addQ($sec4, '4. Date of Decision (mm/dd/yyyy)', 'visaDecisionDate', 'date');
        $this->addQ($sec4, '5. Have you previously applied for permanent residence while in the United States?', 'appliedForPRInUs', 'radio', ['Yes', 'No']);
        $this->addQ($sec4, '6. Have you EVER held lawful permanent resident status which was later rescinded under INA section 246?', 'heldPRRescinded', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec4, '7. Employment and Educational History', 'headingEmpEdHistory', 'heading');
        $this->addQ($sec4, 'Provide ALL of your employment and educational history for the last 5 years as indicated in the Instructions... If you have additional employment or educational history, use the space provided in Part 14. Additional Information.', 'headingEmpHistoryNote', 'heading');
        
        $this->addQ($sec4, 'Employer or School (current or most recent)', 'headingEmp1', 'heading');
        $this->addQ($sec4, 'Name of Employer, Company, or School', 'emp1Name');
        $this->addQ($sec4, 'Your Occupation (if unemployed or retired, so state)', 'emp1Occupation');
        $this->addQ($sec4, 'Address: Street Number and Name', 'emp1Street');
        $this->addQ($sec4, 'Address: Apt. Ste. Flr. Number', 'emp1AptSteFlr');
        $this->addQ($sec4, 'Address: City or Town', 'emp1City');
        $this->addQ($sec4, 'Address: State', 'emp1State');
        $this->addQ($sec4, 'Address: ZIP Code', 'emp1Zip');
        $this->addQ($sec4, 'Address: Province', 'emp1Province');
        $this->addQ($sec4, 'Address: Postal Code', 'emp1PostalCode');
        $this->addQ($sec4, 'Address: Country', 'emp1Country');
        $this->addQ($sec4, 'Dates of Employment...: From (mm/dd/yyyy)', 'emp1From', 'date');
        $this->addQ($sec4, 'Dates of Employment...: To (mm/dd/yyyy)', 'emp1To', 'date');
        $this->addQ($sec4, 'If unemployed or retired, source of financial support:', 'emp1FinancialSupport');

        $this->addQ($sec4, 'Provide your most recent employer or school outside of the United States (if not already listed above).', 'headingOutsideEmpNote', 'heading');
        $this->addQ($sec4, '8. Employer or School Outside US', 'headingEmpOutside', 'heading');
        $this->addQ($sec4, 'Name of Employer, Company, or School', 'empOutsideName');
        $this->addQ($sec4, 'Your Occupation (if unemployed or retired, so state)', 'empOutsideOccupation');
        $this->addQ($sec4, 'Address: Street Number and Name', 'empOutsideStreet');
        $this->addQ($sec4, 'Address: Apt. Ste. Flr. Number', 'empOutsideAptSteFlr');
        $this->addQ($sec4, 'Address: City or Town', 'empOutsideCity');
        $this->addQ($sec4, 'Address: State', 'empOutsideState');
        $this->addQ($sec4, 'Address: ZIP Code', 'empOutsideZip');
        $this->addQ($sec4, 'Address: Province', 'empOutsideProvince');
        $this->addQ($sec4, 'Address: Postal Code', 'empOutsidePostalCode');
        $this->addQ($sec4, 'Address: Country', 'empOutsideCountry');
        $this->addQ($sec4, 'Dates of Employment...: From (mm/dd/yyyy)', 'empOutsideFrom', 'date');
        $this->addQ($sec4, 'Dates of Employment...: To (mm/dd/yyyy)', 'empOutsideTo', 'date');
        $this->addQ($sec4, 'If unemployed or retired, source of financial support:', 'empOutsideFinancialSupport');

        // Part 5. Information About Your Parents
        $sec5 = $form->sections()->create(['title' => 'Part 5. Information About Your Parents', 'order' => 6]);
        $this->addQ($sec5, 'Information About Your Parent 1', 'headingParent1', 'heading');
        $this->addQ($sec5, '1. Parent 1\'s Legal Name: Family Name', 'parent1LastName');
        $this->addQ($sec5, '1. Parent 1\'s Legal Name: Given Name', 'parent1FirstName');
        $this->addQ($sec5, '1. Parent 1\'s Legal Name: Middle Name', 'parent1MiddleName');
        $this->addQ($sec5, '2. Parent 1\'s Name at Birth (if different): Family Name', 'parent1BirthLastName');
        $this->addQ($sec5, '2. Parent 1\'s Name at Birth (if different): Given Name', 'parent1BirthFirstName');
        $this->addQ($sec5, '2. Parent 1\'s Name at Birth (if different): Middle Name', 'parent1BirthMiddleName');
        $this->addQ($sec5, '3. Date of Birth (mm/dd/yyyy)', 'parent1Dob', 'date');
        $this->addQ($sec5, '4. Country of Birth', 'parent1CountryOfBirth');

        $this->addQ($sec5, 'Information About Your Parent 2', 'headingParent2', 'heading');
        $this->addQ($sec5, '5. Parent 2\'s Legal Name: Family Name', 'parent2LastName');
        $this->addQ($sec5, '5. Parent 2\'s Legal Name: Given Name', 'parent2FirstName');
        $this->addQ($sec5, '5. Parent 2\'s Legal Name: Middle Name', 'parent2MiddleName');
        $this->addQ($sec5, '6. Parent 2\'s Name at Birth (if different): Family Name', 'parent2BirthLastName');
        $this->addQ($sec5, '6. Parent 2\'s Name at Birth (if different): Given Name', 'parent2BirthFirstName');
        $this->addQ($sec5, '6. Parent 2\'s Name at Birth (if different): Middle Name', 'parent2BirthMiddleName');
        $this->addQ($sec5, '7. Date of Birth (mm/dd/yyyy)', 'parent2Dob', 'date');
        $this->addQ($sec5, '8. Country of Birth', 'parent2CountryOfBirth');

        // Part 6. Information About Your Marital History
        $sec6 = $form->sections()->create(['title' => 'Part 6. Information About Your Marital History', 'order' => 7]);
        $this->addQ($sec6, '1. What is your current marital status?', 'maritalStatus', 'radio', ['Single, Never Married', 'Married', 'Divorced', 'Widowed', 'Separated', 'Marriage Annulled']);
        $this->addQ($sec6, '2. If you are married, is your spouse a current member of the U.S. armed forces or U.S. Coast Guard?', 'spouseMilitary', 'radio', ['Yes', 'No', 'N/A']);
        $this->addQ($sec6, '3. How many times have you been married (including your current marriage, marriages abroad, annulled marriages, and marriages to the same person)?', 'timesMarried', 'number');

        $this->addQ($sec6, 'Information About Your Current Marriage (including if you are legally separated)', 'headingCurrentMarriage', 'heading');
        $this->addQ($sec6, '4. Current Spouse\'s Legal Name: Family Name', 'currentSpouseLastName');
        $this->addQ($sec6, '4. Current Spouse\'s Legal Name: Given Name', 'currentSpouseFirstName');
        $this->addQ($sec6, '4. Current Spouse\'s Legal Name: Middle Name', 'currentSpouseMiddleName');
        $this->addQ($sec6, '5. Current Spouse\'s A-Number (if any)', 'currentSpouseANumber');
        $this->addQ($sec6, '6. Current Spouse\'s Date of Birth (mm/dd/yyyy)', 'currentSpouseDob', 'date');
        $this->addQ($sec6, '7. Current Spouse\'s Country of Birth', 'currentSpouseCountryOfBirth');
        
        $this->addQ($sec6, '8. Current Spouse\'s Current Physical Address', 'headingSpouseAddress', 'heading');
        $this->addQ($sec6, 'Street Number and Name', 'currentSpouseStreet');
        $this->addQ($sec6, 'Apt. Ste. Flr. Number', 'currentSpouseAptSteFlr');
        $this->addQ($sec6, 'City or Town', 'currentSpouseCity');
        $this->addQ($sec6, 'State', 'currentSpouseState');
        $this->addQ($sec6, 'ZIP Code', 'currentSpouseZip');
        $this->addQ($sec6, 'Province', 'currentSpouseProvince');
        $this->addQ($sec6, 'Postal Code', 'currentSpousePostalCode');
        $this->addQ($sec6, 'Country', 'currentSpouseCountry');
        
        $this->addQ($sec6, '9. Place of Marriage to Current Spouse: City or Town', 'marriagePlaceCity');
        $this->addQ($sec6, '9. Place of Marriage to Current Spouse: State or Province', 'marriagePlaceState');
        $this->addQ($sec6, '9. Place of Marriage to Current Spouse: Country', 'marriagePlaceCountry');
        $this->addQ($sec6, 'Date of Marriage to Current Spouse (mm/dd/yyyy)', 'marriageDate', 'date');
        $this->addQ($sec6, '10. Is your current spouse applying with you?', 'spouseApplyingWithYou', 'radio', ['Yes', 'No']);

        $this->addQ($sec6, 'Information About Prior Marriages (if any)', 'headingPriorMarriage', 'heading');
        $this->addQ($sec6, '11. Prior Spouse\'s Legal Name: Family Name', 'priorSpouseLastName');
        $this->addQ($sec6, '11. Prior Spouse\'s Legal Name: Given Name', 'priorSpouseFirstName');
        $this->addQ($sec6, '11. Prior Spouse\'s Legal Name: Middle Name', 'priorSpouseMiddleName');
        $this->addQ($sec6, '12. Prior Spouse\'s Date of Birth (mm/dd/yyyy)', 'priorSpouseDob', 'date');
        $this->addQ($sec6, '13. Prior Spouse\'s Country of Birth', 'priorSpouseCountryOfBirth');
        $this->addQ($sec6, '14. Prior Spouse\'s Country of Citizenship or Nationality', 'priorSpouseCitizenship');
        $this->addQ($sec6, '15. Date of Marriage to Prior Spouse (mm/dd/yyyy)', 'priorMarriageDate', 'date');
        $this->addQ($sec6, '16. Place of Marriage to Prior Spouse: City or Town', 'priorMarriageCity');
        $this->addQ($sec6, '16. Place of Marriage to Prior Spouse: State or Province', 'priorMarriageState');
        $this->addQ($sec6, '16. Place of Marriage to Prior Spouse: Country', 'priorMarriageCountry');
        $this->addQ($sec6, '17. Place Where Marriage Legally Ended: City or Town', 'priorMarriageEndCity');
        $this->addQ($sec6, '17. Place Where Marriage Legally Ended: State or Province', 'priorMarriageEndState');
        $this->addQ($sec6, '17. Place Where Marriage Legally Ended: Country', 'priorMarriageEndCountry');
        $this->addQ($sec6, 'Date of Marriage with Prior Spouse Legally Ended (mm/dd/yyyy)', 'priorMarriageEndDate', 'date');
        $this->addQ($sec6, '18. How Marriage Ended with Prior Spouse (select one):', 'priorMarriageEndReason', 'radio', ['Annulled', 'Divorced', 'Spouse Deceased', 'Other']);
        $this->addQ($sec6, 'Other (Explain):', 'priorMarriageEndOther');

        // Part 7. Information About Your Children
        $sec7 = $form->sections()->create(['title' => 'Part 7. Information About Your Children', 'order' => 8]);
        $this->addQ($sec7, '1. Indicate the total number of ALL living children anywhere in the world (including adult sons and daughters) that you have.', 'numberOfChildren', 'number');
        $this->addQ($sec7, 'NOTE: The term "children" includes all biological or legally adopted children, as well as current stepchildren, of any age, whether born in the United States or other countries, married or unmarried, living with you or elsewhere and includes any missing children and those born to you outside of marriage.', 'headingChildrenNote', 'heading');
        $this->addQ($sec7, 'Provide the following information for each of your children. If you have more than two children, use the space provided in Part 14. Additional Information.', 'headingChildrenInfoNote', 'heading');
        
        for ($i = 1; $i <= 2; $i++) {
            $this->addQ($sec7, "Child {$i}", "headingChild{$i}", 'heading');
            $this->addQ($sec7, "Current Legal Name: Family Name (Last Name)", "child{$i}LastName");
            $this->addQ($sec7, "Current Legal Name: Given Name (First Name)", "child{$i}FirstName");
            $this->addQ($sec7, "Current Legal Name: Middle Name", "child{$i}MiddleName");
            $this->addQ($sec7, "A-Number (if any)", "child{$i}ANumber");
            $this->addQ($sec7, "Date of Birth (mm/dd/yyyy)", "child{$i}Dob", 'date');
            $this->addQ($sec7, "Country of Birth", "child{$i}CountryOfBirth");
            $this->addQ($sec7, "Is this child also applying now on a separate Form I-485?", "child{$i}Applying", 'radio', ['Yes', 'No']);
            $this->addQ($sec7, "What is your child's relationship to you? (for example, biological child, stepchild, legally adopted child)", "child{$i}Relationship");
        }

        // Part 8. Biographic Information
        $sec8 = $form->sections()->create(['title' => 'Part 8. Biographic Information', 'order' => 9]);
        $this->addQ($sec8, '1. Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec8, '2. Race (Select all applicable boxes)', 'race', 'checkbox', ['White', 'Asian', 'Black or African American', 'American Indian or Alaska Native', 'Native Hawaiian or Other Pacific Islander']);
        $this->addQ($sec8, '3. Height: Feet', 'heightFeet', 'number');
        $this->addQ($sec8, '3. Height: Inches', 'heightInches', 'number');
        $this->addQ($sec8, '4. Weight: Pounds', 'weightPounds', 'number');
        $this->addQ($sec8, '5. Eye color (Select only one box)', 'eyeColor', 'radio', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec8, '6. Hair color (Select only one box)', 'hairColor', 'radio', ['Bald (No hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);

        // Part 9. General Eligibility and Inadmissibility Grounds
        $sec9 = $form->sections()->create(['title' => 'Part 9. General Eligibility and Inadmissibility Grounds', 'order' => 10]);
        $this->addQ($sec9, '1. Have you EVER been a member of, involved in, or in any way associated with any organization, association, fund, foundation, party, club, society, or similar group in the United States or in any other location in the world?', 'q9_1', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answered "Yes" to Item Number 1., complete Item Numbers 2. - 9. If you were a member of more than two organizations, use the space provided in Part 14. Additional Information.', 'headingOrgNote', 'heading');
        
        $this->addQ($sec9, 'Organization 1', 'headingOrg1', 'heading');
        $this->addQ($sec9, '2. Name of Organization', 'org1Name');
        $this->addQ($sec9, '3. City or Town', 'org1City');
        $this->addQ($sec9, '3. State or Province', 'org1State');
        $this->addQ($sec9, '3. Country', 'org1Country');
        $this->addQ($sec9, '4. Nature of Organization, including its purposes and activities, whether illicit or legitimate.', 'org1Nature');
        $this->addQ($sec9, '5. Nature of involvement in organization, including role or positions(s) held, whether illicit or legitimate.', 'org1Involvement');
        $this->addQ($sec9, 'Dates of Membership or Dates of Involvement: From (mm/dd/yyyy)', 'org1From', 'date');
        $this->addQ($sec9, 'Dates of Membership or Dates of Involvement: To (mm/dd/yyyy)', 'org1To', 'date');

        $this->addQ($sec9, 'Organization 2', 'headingOrg2', 'heading');
        $this->addQ($sec9, '6. Name of Organization', 'org2Name');
        $this->addQ($sec9, '7. City or Town', 'org2City');
        $this->addQ($sec9, '7. State or Province', 'org2State');
        $this->addQ($sec9, '7. Country', 'org2Country');
        $this->addQ($sec9, '8. Nature of Organization, including its purposes and activities, whether illicit or legitimate.', 'org2Nature');
        $this->addQ($sec9, '9. Nature of involvement in organization, including role or positions(s) held, whether illicit or legitimate.', 'org2Involvement');
        $this->addQ($sec9, 'Dates of Membership or Dates of Involvement: From (mm/dd/yyyy)', 'org2From', 'date');
        $this->addQ($sec9, 'Dates of Membership or Dates of Involvement: To (mm/dd/yyyy)', 'org2To', 'date');

        $this->addQ($sec9, 'Choose the answer that you think is correct in Part 9. If you answer "Yes" to any questions (or if you answer "No," but are unsure of your answer), provide an explanation of the events and circumstances in the space provided in Part 14. Additional Information.', 'headingPart9Note', 'heading');
        
        $this->addQ($sec9, '10. Have you EVER been denied admission to the United States?', 'q9_10', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '11. Have you EVER been denied a visa to the United States?', 'q9_11', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '12. Have you EVER worked in the United States without authorization?', 'q9_12', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '13. Have you EVER violated the terms or conditions of your nonimmigrant status?', 'q9_13', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '14. Are you presently or have you EVER been in removal, exclusion, rescission, or deportation proceedings, including expedited removal proceedings?', 'q9_14', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '15. Have you EVER been issued a final order of exclusion, deportation, or removal?', 'q9_15', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '16. Have you EVER had a prior final order of exclusion, deportation, or removal reinstated?', 'q9_16', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17. Have you EVER been granted voluntary departure by an immigration officer or an immigration judge but failed to depart within the allotted time?', 'q9_17', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '18. Have you EVER applied for any kind of relief or protection from removal, exclusion, or deportation?', 'q9_18', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '19. Have you EVER been a J nonimmigrant exchange visitor who was subject to the two-year foreign residence requirement?', 'q9_19', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '20. If you answered "Yes" to Item Number 19., have you complied with the foreign residence requirement?', 'q9_20', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '21. If you answered "Yes" to Item Number 19. and "No" to Item Number 20., have you been granted a waiver or has Department of State issued a favorable waiver recommendation letter for you?', 'q9_21', 'radio', ['Yes', 'No']);

        $this->addQ($sec9, 'Criminal Acts and Violations', 'headingCriminalActs', 'heading');
        $this->addQ($sec9, 'For Item Numbers 22. - 41., you must answer "Yes" to any question that applies to you, even if your records were sealed or otherwise cleared, or even if anyone... told you that you no longer have a record... If you answer "Yes"... provide an explanation... that includes a description of the criminal offense; where the criminal offense occurred; when... and the outcome...', 'headingCriminalNote', 'heading');
        
        $this->addQ($sec9, '22. Have you EVER been arrested, cited, charged, or permitted to participate in a diversion program... or detained for any reason by any law enforcement official in any country...?', 'q9_22', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '23. Have you EVER committed a crime of any kind (even if you were not arrested, cited, charged with, or tried for that crime, or convicted)?', 'q9_23', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '24. Have you EVER pled guilty to or been convicted of a crime or offense...?', 'q9_24', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: If you were the beneficiary of a pardon, amnesty, a rehabilitation decree, or other act of clemency, provide documentation of that post-conviction action.', 'headingClemencyNote', 'heading');
        $this->addQ($sec9, '25. Have you EVER been ordered punished by a judge or had conditions imposed on you that restrained your liberty...?', 'q9_25', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '26. Have you EVER violated (or attempted or conspired to violate) any controlled substance law or regulation of a state, the United States, or a foreign country?', 'q9_26', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '27. Have you EVER trafficked in or benefited from, or knowingly aided, abetted, assisted, conspired or colluded in the illegal trafficking of any controlled substances...?', 'q9_27', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '28. Are you the spouse, son, or daughter of an alien who illicitly trafficked or aided... in the illicit trafficking of a controlled substance... and you obtained, within the last 5 years, any financial or other benefit...?', 'q9_28', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '29. If your answer to Item Number 28. is "Yes," did you know or should you have reasonably known that the financial or other benefit you obtained resulted from this activity...?', 'q9_29', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '30. Have you EVER engaged in prostitution or are you coming to the United States to engage in prostitution?', 'q9_30', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '31. Have you EVER directly or indirectly procured or attempted to procure, or imported prostitutes or persons for the purpose of prostitution?', 'q9_31', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '32. Have you EVER received any proceeds or money from prostitution?', 'q9_32', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '33. Do you intend to engage in illegal gambling or any other form of commercialized vice, such as prostitution, bootlegging, or the sale of child pornography, while in the United States?', 'q9_33', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '34. Have you EVER exercised immunity (diplomatic or otherwise) to avoid being prosecuted for a criminal offense in the United States?', 'q9_34', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '35.a. Have you EVER served as a foreign government official?', 'q9_35a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '35.b. If your answer to Item Number 35.a. is "Yes," have you EVER been responsible for, enforced, or directly carried out violations of religious freedoms?', 'q9_35b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '36. Have you EVER induced by force, fraud, or coercion (or otherwise been involved in) the trafficking of another person for commercial sex acts (sex trafficking)?', 'q9_36', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '37. Have you EVER trafficked a person into involuntary servitude, peonage, debt bondage, or slavery? Trafficking includes recruiting, harboring, transporting...', 'q9_37', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: Sex trafficking involves inducing or causing an adult to engage in a commercial sex act... through fraud, force, or coercion...', 'headingSexTraffickingNote', 'heading');
        $this->addQ($sec9, '38. Have you EVER knowingly aided, abetted, assisted, conspired, or colluded with others in trafficking in persons for commercial sex acts or involuntary servitude, peonage, debt bondage, or slavery?', 'q9_38', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '39. Are you the spouse, son, or daughter of an alien who engaged in the trafficking in persons and have received or obtained, within the last 5 years, any financial or other benefits...?', 'q9_39', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '40. If your answer is "Yes" to Item Number 39., did you know or reasonably should have known that this benefit resulted from this activity...?', 'q9_40', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '41. Have you EVER engaged in money laundering or have you EVER knowingly aided, assisted, abetted, conspired, or colluded with others in money laundering or do you seek to enter the United States to engage in such activity?', 'q9_41', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Security and Related', 'headingSecurity', 'heading');
        $this->addQ($sec9, 'Do you intend to:', 'headingIntendTo', 'heading');
        $this->addQ($sec9, '42.a. Engage in any activity that violates or evades any law relating to espionage (including spying) or sabotage in the United States?', 'q9_42a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '42.b. Engage in any activity in the United States that violates or evades any law prohibiting the export from the United States of goods, technology, or sensitive information?', 'q9_42b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '42.c. Engage in any activity whose purpose includes opposing, controlling, or overthrowing the U.S. Government by force, violence, or other unlawful means while in the United States?', 'q9_42c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '42.d. Engage in any other unlawful activity?', 'q9_42d', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Have you EVER:', 'headingEver', 'heading');
        $this->addQ($sec9, '43.a. Received any weapons training, paramilitary training, or other military-type training?', 'q9_43a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.b. Committed kidnapping, assassination, or hijacking or sabotage of a conveyance...?', 'q9_43b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.c. Used a weapon or explosive or any dangerous device with the intent to endanger the safety of another person or people or cause damage to property?', 'q9_43c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.d. Threatened, attempted, conspired, prepared, or planned to do any of the things described in Item Numbers 43.b. - 43.c.?', 'q9_43d', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.e. Incited, under circumstances indicating an intention to cause death or serious bodily harm/injury, any of the activities described in Item Numbers 43.b. - 43.c.?', 'q9_43e', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.f. Participated in, or been a member of, a group or organization that did any of the activities described in Item Numbers 43.b. - 43.e.?', 'q9_43f', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.g. Recruited members or asked for money or things of value for a group or organization that did any of the activities described in Item Numbers 43.b. - 43.e.?', 'q9_43g', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.h. Provided money, a thing of value, services or labor, or any other assistance or support for any of the activities described in Item Numbers 43.b. - 43.e.?', 'q9_43h', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '43.i. Provided money, a thing of value, services or labor, or any other assistance or support for an individual, group, or organization who did any of the activities described in Item Numbers 43.b. - 43.e.?', 'q9_43i', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '44. Do you intend to engage in any of the activities listed in any part of Item Numbers 43.b. - 43.e.?', 'q9_44', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '45. Do you intend to engage in any activity that could endanger the welfare, safety, or security of the United States?', 'q9_45', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: If you answered "Yes" to any part of Item Numbers 42.a. - 45., explain what you did, including the dates and location of the circumstances, or what you intend to do in the space provided in Part 14. Additional Information.', 'headingExplain42_45', 'heading');

        $this->addQ($sec9, '46. Are you the spouse or child of an individual who EVER engaged in any of the activities listed in Item Numbers 43.b. - 43.i.?', 'q9_46', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: If you answered "Yes" to any part of Item Number 46., explain what your parent or spouse did, including the dates and location of the circumstances in Part 14. Additional Information.', 'headingExplain46', 'heading');
        
        $this->addQ($sec9, '47. Have you EVER sold, provided, or transported weapons, or assisted any person in selling, providing, or transporting weapons, which you knew or believed would be used against another person?', 'q9_47', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '48. Have you EVER worked, volunteered, or otherwise served in any prison, jail, prison camp, detention facility, labor camp, or any other place where people were detained...?', 'q9_48', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '49. Have you EVER been a member of, assisted, or participated in any group, unit, or organization of any kind in which you or other persons used any type of weapon against any person or threatened to do so?', 'q9_49', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '50. Have you EVER served in, been a member of, assisted (helped), or participated in any military or police unit?', 'q9_50', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '51. Have you EVER served in, been a member of, assisted (helped), or participated in any armed group (a group that carries weapons)...?', 'q9_51', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answered "Yes" to Item Number 50. or 51., include the name of the country, the name of the military unit or armed group, your rank or position, and your dates of involvement in your explanation in Part 14. Additional Information.', 'headingExplain50_51', 'heading');
        
        $this->addQ($sec9, '52. Have you EVER been a member of, or in any way affiliated with, the Communist Party or any totalitarian party (in the United States or abroad)?', 'q9_52', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Have you EVER ordered, incited, called for, committed, assisted, helped with, or otherwise participated in any of the following:', 'headingCrimes2', 'heading');
        $this->addQ($sec9, '53.a. Torture?', 'q9_53a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '53.b. Genocide?', 'q9_53b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '53.c. Killing, or trying to kill, any person?', 'q9_53c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '53.d. Intentionally and severely injuring or trying to injure any person?', 'q9_53d', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '54. Have you EVER recruited, enlisted, conscripted, or used any person under 15 years of age to take part in hostilities or to serve in or help an armed force or group...?', 'q9_54', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '55. Have you EVER used any person under 15 years of age to take part in hostilities, for instance, participating in combat or providing services related to combat...?', 'q9_55', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: If you answered "Yes" to any part of Item Numbers 47. - 55., explain what occurred, including the dates and location of the circumstances, in the space provided in Part 14. Additional Information.', 'headingExplain47_55', 'heading');

        $this->addQ($sec9, 'Public Charge', 'headingPublicCharge', 'heading');
        $this->addQ($sec9, 'Each alien who is subject to the public charge ground of inadmissibility in INA section 212(a)(4) must complete Item Numbers 57. - 66. An alien is subject to the public charge ground of inadmissibility if the alien does not fall under one of the categories exempt... If you fall under one of the exempt categories listed below, please select the exempt category, and skip Item Numbers 57. - 66. If you do not fall under one of the exempt categories listed below, select "I do not fall under any of the exempt categories listed above and will complete Item Numbers 57. - 66."', 'headingPublicChargeNote', 'heading');
        $this->addQ($sec9, 'NOTE: For more information, see Part 9. General Eligibility and Inadmissibility Grounds, Public Charge section of these Instructions.', 'headingPublicChargeInfo', 'heading');
        
        $this->addQ($sec9, '56. I am exempt from the public charge ground of inadmissibility because I am a/an (select only one box):', 'publicChargeExemption', 'select', [
            'VAWA Self-Petitioner (Form I-360)',
            'Special Immigrant Juvenile (Form I-360)',
            'Certain Afghan or Iraqi National (Form I-360 or Form DS-157)',
            'Asylee (Form I-589 or Form I-730)',
            'Refugee (Form I-590 or Form I-730)',
            'Victim of Qualifying Criminal Activity (U Nonimmigrant) under INA section 245(m)',
            'Any category other than INA section 245(m), but you are in valid U nonimmigrant status at the time you file',
            'Human Trafficking Victim (T nonimmigrant) under INA section 245(l)',
            'Any category other than INA section 245(l), but you either have a pending application for T nonimmigrant status',
            'Cuban Adjustment Act',
            'Cuban Adjustment Act for Battered Spouses and Children',
            'Dependent Status under the Haitian Refugee Immigrant Fairness Act',
            'Dependent Status under the Haitian Refugee Immigrant Fairness Act for Battered Spouses and Children',
            'Cuban and Haitian Entrants Applying for Adjustment of Status under section 202 of the Immigration Reform and Control Act of 1986',
            'A Lautenberg Parolee',
            'National of Vietnam, Cambodia, or Laos Applying under the Foreign Operations, Export Financing, and Related Programs',
            'Continuous Residence in the United States Since Before January 1, 1972 (“Registry”)',
            'Amerasian Homecoming Act',
            'Polish or Hungarian Parolee',
            'Nicaraguans and Other Central Americans under section 203 of NACARA',
            'American Indian Born in Canada (INA section 289) or the Texas Band of Kickapoo Indians',
            'Section 7611 of the National Defense Authorization Act for Fiscal Year 2020 (Liberian Refugee Immigration Fairness)',
            'Spouse, Child, or Parent of a U.S. Active-Duty Service Member in the Armed Forces under the NDAA',
            'I do not fall under any of the exempt categories listed above and will complete Item Numbers 57. - 66.'
        ]);
        
        $this->addQ($sec9, 'If you selected "I do not fall under any of the exempt categories listed above and will complete Item Numbers 57. - 66." in Item Number 56., complete Item Numbers 57. - 66. below. If you selected an exempt category in Item Number 56., go to Item Number 67. If you need extra space to complete this section, use the space provided in Part 14. Additional Information.', 'headingPublicChargeCompleteNote', 'heading');
        
        $this->addQ($sec9, '57. What is the size of your household?', 'householdSize', 'number');
        $this->addQ($sec9, '58. Indicate your annual household income.', 'householdIncome', 'radio', ['$0-27,000', '$27,001-52,000', '$52,001-85,000', '$85,001-141,000', 'Over $141,000']);
        $this->addQ($sec9, '59. Identify the total value of your household assets.', 'householdAssets', 'radio', ['$0-18,400', '$18,401-136,000', '$136,001-321,400', '$321,401-707,100', 'Over $707,100']);
        $this->addQ($sec9, '60. Identify the total value of your household liabilities (including both secured and unsecured liabilities).', 'householdLiabilities', 'radio', ['$0', '$1-10,100', '$10,101-57,700', '$57,701-186,800', 'Over $186,800']);
        
        $this->addQ($sec9, '61. What is the highest degree or grade of school you have completed?', 'highestDegree', 'select', [
            'Less than a high school diploma.',
            'High school diploma, GED, or alternative credential',
            '1 or more years of college credit, no degree',
            'Associate\'s degree',
            'Bachelor\'s degree',
            'Master\'s degree',
            'Professional degree (JD, MD, DMD, etc.)',
            'Doctorate degree'
        ]);
        $this->addQ($sec9, 'If you select less than a high school diploma, indicate the highest grade of school you have completed.', 'highestGrade');
        
        $this->addQ($sec9, '62. List your certifications, licenses, skills obtained through work experience, and educational certificates.', 'certifications', 'textarea');
        
        $this->addQ($sec9, '63. Have you ever received Supplemental Security Income (SSI), Temporary Assistance for Needy Families (TANF), or state, Tribal, territorial, or local cash benefit programs for income maintenance...?', 'q9_63', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '64. Have you ever received long-term institutionalization at government expense?', 'q9_64', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '65. If your answer to Item Number 63. is "Yes," list the specific benefit(s) you received, the start and end dates of each period of receipt, the dollar amount of benefits received, and whether you received the benefits while you were in an immigration category exempt from the public charge ground of inadmissibility.', 'q9_65_benefits', 'textarea');
        $this->addQ($sec9, '66. If your answer to Item Number 64. is "Yes," list the name, city, and state for each institution, the start and end dates of each period of institutionalization, the reason you were institutionalized, and whether you were institutionalized while you were in an immigration category exempt from the public charge ground of inadmissibility.', 'q9_66_institutions', 'textarea');
        
        $this->addQ($sec9, '67. Have you EVER failed or refused to attend or to remain in attendance at any removal proceeding filed against you on or after April 1, 1997?', 'q9_67', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: If your answer to Item Number 67. is "Yes," attach a written statement explaining why you failed or refused to attend or remain in attendance at the removal proceeding, including any explanation of a reasonable cause for that failure or refusal.', 'headingExplain67', 'heading');
        
        $this->addQ($sec9, '68. Have you EVER submitted altered, fraudulent, or counterfeit documentation to any U.S. Government official to obtain or attempt to obtain any immigration benefit, including a visa or entry into the United States?', 'q9_68', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Illegal Entries and Other Immigration Violations', 'headingIllegalEntries', 'heading');
        $this->addQ($sec9, '69. Have you EVER lied about, concealed, or misrepresented any information on an application or petition to obtain a visa, other documentation required for entry into the United States, admission to the United States, or any other kind of immigration benefit?', 'q9_69', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '70. Have you EVER falsely claimed to be a U.S. citizen (in writing or any other way)?', 'q9_70', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '71. Have you EVER been a stowaway on a vessel or aircraft arriving in the United States?', 'q9_71', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '72. Are you under a final order of civil penalty for violating INA section 274C for use of fraudulent documents?', 'q9_72', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '73. Have you EVER knowingly encouraged, induced, assisted, abetted, or aided any alien to enter or to try to enter the United States illegally (alien smuggling)?', 'q9_73', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Removal, Unlawful Presence, or Illegal Reentry After Previous Immigration Violations', 'headingRemoval', 'heading');
        $this->addQ($sec9, '74. Have you EVER been excluded, deported, or removed from the United States or have you ever departed the United States on your own after having been ordered excluded, deported, or removed from the United States?', 'q9_74', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '75. Have you EVER entered the United States without being inspected and admitted or paroled?', 'q9_75', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '76. Since April 1, 1997, have you been unlawfully present in the United States? You were unlawfully present in the United States if you were present in the United States after the expiration of the period of stay authorized by the Department of Homeland Security (DHS) Secretary or were present in the United States without being admitted or paroled.', 'q9_76', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: If you answered "Yes" to Item Number 76., give the dates of unlawful presence in the space provided in Part 14. Additional Information.', 'headingExplain76', 'heading');
        $this->addQ($sec9, '77. If you answered "Yes" to Item Number 76., was a severe form of trafficking in persons at least one central reason for your unlawful presence in the United States?', 'q9_77', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'NOTE: Severe trafficking in persons involves sex trafficking (the recruitment, harboring, transportation, provision, or obtaining of a person to commit a commercial sex act) induced by force, fraud, coercion...', 'headingSevereTraffickingNote', 'heading');
        
        $this->addQ($sec9, 'Since April 1, 1997, have you EVER reentered or attempted to reenter the United States without being inspected and admitted or paroled after:', 'headingReentry', 'heading');
        $this->addQ($sec9, '78.a. Having been unlawfully present in the United States for more than one year in the aggregate on or after April 1, 1997?...', 'q9_78a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '78.b. Having been deported, excluded, or removed from the United States?', 'q9_78b', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Miscellaneous Conduct', 'headingMiscConduct', 'heading');
        $this->addQ($sec9, '79. Do you plan to practice polygamy in the United States?', 'q9_79', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '80. Are you accompanying an alien who is inadmissible and who has been certified by a medical officer to be helpless from sickness, mental or physical disability, or infancy pursuant to INA section 232(b)?', 'q9_80', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '81. Have you EVER assisted in detaining, retaining, or withholding custody of a U.S. citizen child outside the United States from a U.S. citizen who has been granted custody of the child?', 'q9_81', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '82. Have you EVER voted in violation of any Federal, state, or local constitutional provision, statute, ordinance, or regulation?', 'q9_82', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '83. Have you EVER renounced U.S. citizenship for the purpose of avoiding taxation?', 'q9_83', 'radio', ['Yes', 'No']);

        $this->addQ($sec9, 'Have you EVER:', 'headingHaveYouEver', 'heading');
        $this->addQ($sec9, '84.a. Applied for exemption or discharge from training or service in the U.S. armed forces or in the U.S. National Security Training Corps on the ground that you are an alien?', 'q9_84a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '84.b. Been relieved or discharged from such training or service on the ground that you are an alien?', 'q9_84b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '84.c. Been convicted of desertion from the U.S. armed forces?', 'q9_84c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '85. Have you EVER left or remained outside the United States to avoid or evade training or service in the U.S. armed forces in time of war or a period declared by the President to be a national emergency?', 'q9_85', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '86. If you answered "Yes" to Item Number 85., what was your nationality or immigration status immediately before you left?', 'q9_86');

        // Part 10. Applicant's Contact Information, Certification, and Signature
        $sec10 = $form->sections()->create(['title' => 'Part 10. Applicant\'s Contact Information, Certification, and Signature', 'order' => 10]);
        $this->addQ($sec10, 'Applicant\'s Contact Information', 'headingApplicantContact', 'heading');
        $this->addQ($sec10, '1. Applicant\'s Daytime Telephone Number', 'applicantDaytimePhone');
        $this->addQ($sec10, '2. Applicant\'s Mobile Telephone Number (if any)', 'applicantMobilePhone');
        $this->addQ($sec10, '3. Applicant\'s Email Address (if any)', 'applicantEmail');

        $this->addQ($sec10, 'Applicant\'s Certification and Signature', 'headingApplicantSignature', 'heading');
        $this->addQ($sec10, 'I certify, under penalty of perjury, that I provided or authorized all of the responses and information contained in and submitted with my application...', 'applicantCert', 'checkbox', ['I agree']);
        $this->addQ($sec10, '4. Applicant\'s Signature', 'applicantSignature', 'signature');
        $this->addQ($sec10, 'Date of Signature (mm/dd/yyyy)', 'applicantSignatureDate', 'date');

        // Part 11. Interpreter's Contact Information, Certification, and Signature
        $sec11 = $form->sections()->create(['title' => 'Part 11. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 11]);
        $this->addQ($sec11, 'Interpreter\'s Full Name', 'headingInterpreterName', 'heading');
        $this->addQ($sec11, '1. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec11, 'Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec11, 'Interpreter\'s Business or Organization Name (if any)', 'interpreterBusiness');

        echo "Successfully seeded I-485 form (up to Part 11)!\n";
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
