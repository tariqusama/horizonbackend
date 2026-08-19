<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;

class FormN400Seeder extends Seeder
{
    public function run(): void
    {
        $service = Service::where('title', 'like', '%N-400%')->orWhere('subtitle', 'like', '%N-400%')->first();
        if (!$service) {
            echo "Service N-400 not found.\n";
        }
        $serviceId = $service ? $service->id : null;

        $form = DynamicForm::updateOrCreate(
            ['slug' => 'n-400'],
            ['name' => 'Application for Naturalization', 'description' => 'Form N-400']
        );

        if ($serviceId) { 
            $form->services()->syncWithoutDetaching([$serviceId]); 
        }
        $form->sections()->delete();

        // Part 1. Information About Your Eligibility
        $sec1 = $form->sections()->create(['title' => 'Part 1. Information About Your Eligibility', 'order' => 1]);
        $this->addQ($sec1, '1. Reason for Filing (Please see Instructions for eligibility requirements under each provision. Select only one box to identify the basis of your eligibility or your Form N-400 may be delayed or rejected.)', 'reasonForFiling', 'radio', [
            'A. General Provision',
            'B. Spouse of U.S. Citizen',
            'C. VAWA',
            'D. Spouse of U.S. Citizen in Qualified Employment Outside the United States',
            'E. Military Service During Period of Hostilities',
            'F. At Least One Year of Honorable Military Service at Any Time',
            'G. Other Reason for Filing Not Listed Above'
        ]);
        $this->addQ($sec1, 'If your mother or father (including legal adoptive mother or father) is a U.S. citizen by birth, or was naturalized before you reached your 18th birthday, you may not need to file Form N-400 as you may already be a U.S. citizen. Before you file this application, please visit the USCIS website at www.uscis.gov/N-600 for Form N-600, Application for Certificate of Citizenship.', 'headingCitizenshipNote', 'heading');
        $this->addQ($sec1, 'If your residential address is outside the United States and you are filing under Immigration and Nationality Act (INA) section 319(b), select the USCIS field office where you would like to have your naturalization interview. You can find a USCIS field office at www.uscis.gov/field-offices.', 'headingFieldOfficeNote', 'heading');

        // Part 2. Information About You
        $sec2 = $form->sections()->create(['title' => 'Part 2. Information About You (Person applying for naturalization)', 'order' => 2]);
        $this->addQ($sec2, '1. Your Current Legal Name (do not provide a nickname)', 'headingCurrentName', 'heading');
        $this->addQ($sec2, '1.a. Family Name (Last Name)', 'lastName');
        $this->addQ($sec2, '1.b. Given Name (First Name)', 'firstName');
        $this->addQ($sec2, '1.c. Middle Name (if applicable)', 'middleName');

        $this->addQ($sec2, '2. Other Names You Have Used Since Birth (see the Instructions for this Item Number for more information about which names to include)', 'headingOtherNames', 'heading');
        $this->addQ($sec2, '2.a. Family Name (Last Name)', 'otherLastName');
        $this->addQ($sec2, '2.b. Given Name (First Name)', 'otherFirstName');
        $this->addQ($sec2, '2.c. Middle Name (if applicable)', 'otherMiddleName');

        $this->addQ($sec2, 'Name Change (Optional)', 'headingNameChange', 'heading');
        $this->addQ($sec2, 'Read the Instructions for this Item Number before you decide whether you would like to legally change your name.', 'headingNameChangeInstructions', 'heading');
        $this->addQ($sec2, '3. Would you like to legally change your name?', 'wantNameChange', 'radio', ['Yes', 'No (skip to Item Number 4.)']);
        $this->addQ($sec2, 'If you answered "Yes," type or print the new name you would like to use:', 'headingNameChangeNote', 'heading');
        $this->addQ($sec2, 'New Family Name (Last Name)', 'newNameLastName');
        $this->addQ($sec2, 'New Given Name (First Name)', 'newNameFirstName');
        $this->addQ($sec2, 'New Middle Name (if applicable)', 'newNameMiddleName');

        $this->addQ($sec2, 'Other Information', 'headingOtherInfoPart2', 'heading');
        $this->addQ($sec2, '4. USCIS Online Account Number (if any)', 'uscisAccountNumber');
        $this->addQ($sec2, '5. Sex', 'sex', 'radio', ['Male', 'Female']);
        
        $this->addQ($sec2, 'In addition to your actual date of birth, include any other dates of birth you have ever used, including dates used in connection with any legal names or non-legal names, in the space provided in Part 14. Additional Information.', 'headingDobNamesNote', 'heading');
        $this->addQ($sec2, '6. Date of Birth (mm/dd/yyyy)', 'dob', 'date');
        $this->addQ($sec2, '7. If you are a lawful permanent resident, provide the date you became a lawful permanent resident (mm/dd/yyyy)', 'lprDate', 'date');
        $this->addQ($sec2, '8. Country of Birth', 'countryOfBirth');
        $this->addQ($sec2, '9. Country of Citizenship or Nationality', 'countryOfCitizenship');
        $this->addQ($sec2, 'If you are a citizen or national of more than one country, list additional countries of nationality in the space provided in Part 14. Additional Information.', 'headingCitizenshipNote2', 'heading');
        
        $this->addQ($sec2, '10. Was your mother or father (including adoptive mother or father) a U.S. citizen before your 18th birthday?', 'parentsCitizen', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If you answered "Yes," you may already be a U.S. citizen. If you are a U.S. citizen, you should not complete Form N-400.', 'headingParentsCitizenNote', 'heading');

        $this->addQ($sec2, '11. Do you have a physical or developmental disability or mental impairment that prevents you from demonstrating your knowledge and understanding of the English language or civics requirements for naturalization?', 'disabilityImpairment', 'radio', ['Yes', 'No']);
        $this->addQ($sec2, 'If you answered "Yes," submit a completed Form N-648, Medical Certification for Disability Exceptions, when you file your Form N-400. See the Naturalization Testing and Exceptions section of the Instructions for additional information about exceptions from the English language test, including exceptions based on age and years as a lawful permanent resident.', 'headingDisabilityNote', 'heading');

        $this->addQ($sec2, 'Social Security Update', 'headingSsaUpdate', 'heading');
        $this->addQ($sec2, '12.a. Do you want the Social Security Administration (SSA) to issue you an original or replacement Social Security card and update your immigration status with the SSA if and when you are naturalized?', 'wantSsaUpdate', 'radio', ['No (Go to Part 3.)', 'Yes (Complete Item Numbers 12.b. - 12.c.)']);
        $this->addQ($sec2, '12.b. Provide your Social Security number (SSN) (if any).', 'ssnNumber');
        $this->addQ($sec2, '12.c. Consent for Disclosure: I authorize disclosure of information from this application and USCIS systems to the SSA as required for the purpose of assigning me an SSN, issuing me an original or replacement Social Security card, and updating my immigration status with the SSA.', 'ssaConsent', 'checkbox', ['Yes', 'No']);
        $this->addQ($sec2, 'NOTE: If you answered "Yes" to Item Number 12.a., you must also answer "Yes" to Item Number 12.c., Consent for Disclosure, to receive a card.', 'headingSsaConsentNote', 'heading');

        // Part 3. Biographic Information
        $sec3 = $form->sections()->create(['title' => 'Part 3. Biographic Information', 'order' => 3]);
        $this->addQ($sec3, 'NOTE: USCIS requires you to complete the categories below to conduct background checks. (See the Form N-400 Instructions for more information.)', 'headingBiographicNote', 'heading');
        $this->addQ($sec3, '1. Ethnicity (Select only one box)', 'ethnicity', 'radio', ['Hispanic or Latino', 'Not Hispanic or Latino']);
        $this->addQ($sec3, '2. Race (Select all applicable boxes)', 'race', 'checkbox', ['White', 'Asian', 'Black or African American', 'American Indian or Alaska Native', 'Native Hawaiian or Other Pacific Islander']);
        $this->addQ($sec3, '3. Height: Feet', 'heightFeet', 'number');
        $this->addQ($sec3, '3. Height: Inches', 'heightInches', 'number');
        $this->addQ($sec3, '4. Weight: Pounds', 'weightPounds', 'number');
        $this->addQ($sec3, '5. Eye color (Select only one box)', 'eyeColor', 'radio', ['Black', 'Blue', 'Brown', 'Gray', 'Green', 'Hazel', 'Maroon', 'Pink', 'Unknown/Other']);
        $this->addQ($sec3, '6. Hair color (Select only one box)', 'hairColor', 'radio', ['Bald (No hair)', 'Black', 'Blond', 'Brown', 'Gray', 'Red', 'Sandy', 'White', 'Unknown/Other']);

        // Part 4. Information About Your Residence
        $sec4 = $form->sections()->create(['title' => 'Part 4. Information About Your Residence', 'order' => 4]);
        $this->addQ($sec4, '1. Physical Addresses', 'headingPhysicalAddresses', 'heading');
        $this->addQ($sec4, 'List every location where you have lived during the last 5 years if you are filing based on the general provision under Part 1., Item Number 1.a. If you are filing based on other naturalization eligibility options, see Part 4. in the Specific Instructions by Item Number section of the Instructions for the applicable period of time for which you must enter this information. If you need extra space, use the space provided in Part 14. Additional Information.', 'headingPhysicalAddressesNote', 'heading');
        
        $this->addQ($sec4, 'Current Physical Address', 'headingCurrentPhysical', 'heading');
        $this->addQ($sec4, 'In Care Of Name (if any)', 'physicalInCareOf');
        $this->addQ($sec4, 'Street Number and Name', 'physicalStreet');
        $this->addQ($sec4, 'Apt. Ste. Flr. Number', 'physicalAptSteFlr');
        $this->addQ($sec4, 'City or Town', 'physicalCity');
        $this->addQ($sec4, 'State / Province', 'physicalState');
        $this->addQ($sec4, 'ZIP Code / Postal Code', 'physicalZip');
        $this->addQ($sec4, 'Country', 'physicalCountry');
        $this->addQ($sec4, 'Dates of Residence: From (mm/dd/yyyy)', 'physicalDateFrom', 'date');
        $this->addQ($sec4, 'Dates of Residence: To (mm/dd/yyyy) [Leave blank if PRESENT]', 'physicalDateTo', 'date');

        $this->addQ($sec4, '2. Is your current physical address also your current mailing address?', 'physicalSameAsMailing', 'radio', ['Yes (If you answered "Yes," skip to Part 5.)', 'No']);
        
        $this->addQ($sec4, '3. Current Mailing Address (Safe Mailing Address, if applicable)', 'headingCurrentMailing', 'heading');
        $this->addQ($sec4, 'In Care Of Name (if any)', 'mailingInCareOf');
        $this->addQ($sec4, 'Street Number and Name', 'mailingStreet');
        $this->addQ($sec4, 'Apt. Ste. Flr. Number', 'mailingAptSteFlr');
        $this->addQ($sec4, 'City or Town', 'mailingCity');
        $this->addQ($sec4, 'State', 'mailingState');
        $this->addQ($sec4, 'ZIP Code', 'mailingZip');
        $this->addQ($sec4, 'Province', 'mailingProvince');
        $this->addQ($sec4, 'Postal Code', 'mailingPostalCode');
        $this->addQ($sec4, 'Country', 'mailingCountry');

        // Part 5. Information About Your Marital History
        $sec5 = $form->sections()->create(['title' => 'Part 5. Information About Your Marital History', 'order' => 5]);
        $this->addQ($sec5, '1. What is your current marital status?', 'maritalStatus', 'radio', ['Single, Never Married', 'Married', 'Divorced', 'Widowed', 'Separated', 'Marriage Annulled']);
        $this->addQ($sec5, '2. If you are currently married, is your spouse a current member of the U.S. armed forces?', 'spouseMilitary', 'radio', ['Yes', 'No']);
        $this->addQ($sec5, '3. How many times have you been married? (See the Specific Instructions by Item Number section of the Instructions for more information about which marriages to include.)', 'timesMarried', 'number');
        $this->addQ($sec5, 'If you are single and have never married, go to Part 6. Information About Your Children.', 'headingSingleNote', 'heading');
        $this->addQ($sec5, 'Provide current marriage certificate and any divorce decree, annulment decree, or death certificate showing that your prior marriages were terminated (if applicable).', 'headingMarriageCertNote', 'heading');
        
        $this->addQ($sec5, 'Your Current Marriage', 'headingCurrentMarriage', 'heading');
        $this->addQ($sec5, 'If you are currently married, including if you are legally separated, provide the following information about your current spouse.', 'headingCurrentMarriageNote', 'heading');
        $this->addQ($sec5, '4.a. Current Spouse\'s Legal Name: Family Name (Last Name)', 'spouseLastName');
        $this->addQ($sec5, '4.a. Current Spouse\'s Legal Name: Given Name (First Name)', 'spouseFirstName');
        $this->addQ($sec5, '4.a. Current Spouse\'s Legal Name: Middle Name (if applicable)', 'spouseMiddleName');
        $this->addQ($sec5, '4.b. Current Spouse\'s Date of Birth (mm/dd/yyyy)', 'spouseDob', 'date');
        $this->addQ($sec5, '4.c. Date You Entered into Marriage with Current Spouse (mm/dd/yyyy)', 'spouseMarriageDate', 'date');
        $this->addQ($sec5, '4.d. Is your current spouse\'s present physical address the same as your physical address?', 'spouseAddressSame', 'radio', ['Yes', 'No (If you answered "No," provide address in Part 14. Additional Information.)']);
        
        $this->addQ($sec5, 'If you are filing under one of the categories below, answer Item Numbers 4.a. - 8.: Spouse of U.S. Citizen, Part 1., Item Number 1.b.; or; Spouse of U.S. Citizen in Qualified Employment Outside the United States, Part 1., Item Number 1.d.', 'headingSpouseCitizenCondition', 'heading');
        $this->addQ($sec5, '5.a. When did your current spouse become a U.S. citizen?', 'spouseCitizenWhen', 'radio', ['By Birth in the United States - Go to Item Number 7.', 'Other - Complete Item Number 5.b.']);
        $this->addQ($sec5, '5.b. Date Your Current Spouse Became a U.S. Citizen (mm/dd/yyyy)', 'spouseCitizenDate', 'date');
        $this->addQ($sec5, '6. Current Spouse\'s Alien Registration Number (A-Number) (if any)', 'spouseANumber');
        $this->addQ($sec5, '7. How many times has your current spouse been married? (See the Specific Instructions by Item Number section of the Instructions for more information about which marriages to include.)', 'spouseTimesMarried', 'number');
        $this->addQ($sec5, 'Provide divorce decrees, annulment decrees, or death certificates showing that all of your spouse\'s prior marriages were terminated (if applicable).', 'headingSpouseDivorceNote', 'heading');
        
        $this->addQ($sec5, 'Only answer Item Number 8. if you are filing under Part 1., Item Number 1.d., Spouse of U.S. Citizen in Qualified Employment Outside the United States.', 'headingPart5Item8Note', 'heading');
        $this->addQ($sec5, '8. Current Spouse\'s Current Employer or Company', 'spouseEmployer');

        // Part 6. Information About Your Children
        $sec6 = $form->sections()->create(['title' => 'Part 6. Information About Your Children', 'order' => 6]);
        $this->addQ($sec6, '1. Indicate your total number of children under 18 years of age.', 'numberOfChildren', 'number');
        $this->addQ($sec6, '2. Provide the following information about your children identified in Item Number 1. For the residence and relationship columns, you must type or print one of the valid options listed...', 'headingChildrenInfoNote', 'heading');
        
        for ($i = 1; $i <= 3; $i++) {
            $this->addQ($sec6, "Child {$i}: Son or Daughter's Name (First Name and Family Name)", "child{$i}Name");
            $this->addQ($sec6, "Child {$i}: Date of Birth (mm/dd/yyyy)", "child{$i}Dob", 'date');
            $this->addQ($sec6, "Child {$i}: Residence (Valid options include: resides with me, does not reside with me, or unknown/missing)", "child{$i}Residence");
            $this->addQ($sec6, "Child {$i}: Relationship (Valid options include: biological son or daughter, stepchild, or legally adopted son or daughter)", "child{$i}Relationship");
            $this->addQ($sec6, "Child {$i}: Are you providing support for your son or daughter?", "child{$i}Support", 'radio', ['Yes', 'No']);
        }

        // Part 7. Information About Your Employment and Schools You Attended
        $sec7 = $form->sections()->create(['title' => 'Part 7. Information About Your Employment and Schools You Attended', 'order' => 7]);
        $this->addQ($sec7, 'List where you have worked or attended school full time or part time during the last 5 years... Provide information for the complete time period for all employment, including foreign government employment such as military, police, and intelligence services...', 'headingEmploymentNote', 'heading');
        
        for ($i = 1; $i <= 3; $i++) {
            $this->addQ($sec7, "Employment/School {$i}", "headingEmployment{$i}", 'heading');
            $this->addQ($sec7, "Employer or School", "emp{$i}Name");
            $this->addQ($sec7, "City/Town", "emp{$i}City");
            $this->addQ($sec7, "State/Province", "emp{$i}State");
            $this->addQ($sec7, "ZIP Code/Postal Code", "emp{$i}Zip");
            $this->addQ($sec7, "Country", "emp{$i}Country");
            $this->addQ($sec7, "Occupation or Field of Study", "emp{$i}Occupation");
            $this->addQ($sec7, "Dates: From (mm/dd/yyyy)", "emp{$i}From", 'date');
            $this->addQ($sec7, "Dates: To (mm/dd/yyyy) [Leave blank if PRESENT]", "emp{$i}To", 'date');
        }

        // Part 8. Time Outside the United States
        $sec8 = $form->sections()->create(['title' => 'Part 8. Time Outside the United States', 'order' => 8]);
        $this->addQ($sec8, 'List below all the trips that you have taken outside the United States during the last 5 years... Do not include day trips (where the entire trip was completed within 24 hours) in the table...', 'headingTripsNote', 'heading');
        
        for ($i = 1; $i <= 3; $i++) {
            $this->addQ($sec8, "Trip {$i}", "headingTrip{$i}", 'heading');
            $this->addQ($sec8, "Date You Left the United States (mm/dd/yyyy)", "trip{$i}Left", 'date');
            $this->addQ($sec8, "Date You Returned to the United States (mm/dd/yyyy)", "trip{$i}Returned", 'date');
            $this->addQ($sec8, "Countries to Which You Traveled", "trip{$i}Countries");
        }

        // Part 9. Additional Information About You
        $sec9 = $form->sections()->create(['title' => 'Part 9. Additional Information About You', 'order' => 9]);
        $this->addQ($sec9, 'When a question includes the word "EVER," you must provide information about any of your actions or conduct that occurred anywhere in the world at any time...', 'headingEverNote', 'heading');
        
        $this->addQ($sec9, '1. Have you EVER claimed to be a U.S. citizen (in writing or any other way)?', 'q9_1', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '2. Have you EVER registered to vote or voted in any Federal, state, or local election in the United States?', 'q9_2', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '3. Do you currently owe any overdue Federal, state, or local taxes in the United States?', 'q9_3', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '4. Since you became a lawful permanent resident, have you called yourself a "nonresident alien" on a Federal, state, or local tax return...', 'q9_4', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '5.a. Have you EVER been a member of, involved in, or in any way associated with any Communist or totalitarian party anywhere in the world?', 'q9_5a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '5.b. Have you EVER advocated (supported and promoted) any of the following, or been a member of... any group... that advocated: The overthrow by force... Opposition to all organized government; World communism; The establishment... of a totalitarian dictatorship; The unlawful assaulting or killing of any officer... The unlawful damage... of property; or Sabotage?', 'q9_5b', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '6.a. Have you EVER used a weapon or explosive with intent to harm another person or cause damage to property?', 'q9_6a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '6.b. Have you EVER engaged (participated) in kidnapping, assassination, or hijacking or sabotage...?', 'q9_6b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '6.c. Have you EVER threatened, attempted (tried), conspired... advocated for, or incited others to commit any of the acts listed in 6.a or 6.b?', 'q9_6c', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Have you EVER ordered, incited, called for, committed, assisted, helped with, or otherwise participated in any of the following:', 'headingCrimesNote', 'heading');
        $this->addQ($sec9, '7.a. Torture?', 'q9_7a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '7.b. Genocide?', 'q9_7b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '7.c. Killing or trying to kill any person?', 'q9_7c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '7.d. Intentionally and severely injuring or trying to injure any person?', 'q9_7d', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '7.e. Any kind of sexual contact or activity with any person who did not consent...?', 'q9_7e', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '7.f. Not letting someone practice his or her religion?', 'q9_7f', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '7.g. Causing harm or suffering to any person because of his or her race, religion, national origin, membership in a particular social group, or political opinion?', 'q9_7g', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '8.a. Have you EVER served in, been a member of, assisted (helped), or participated in any military or police unit?', 'q9_8a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '8.b. Have you EVER served in, been a member of, assisted (helped), or participated in any armed group (a group that carries weapons)...?', 'q9_8b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answered "Yes" to Item Number 8.a. or Item Number 8.b., include the name of the country, the name of the military unit or armed group, your rank or position, and your dates of involvement in your explanation in Part 14. Additional Information.', 'headingMilitaryNote', 'heading');
        
        $this->addQ($sec9, '9. Have you EVER worked, volunteered, or otherwise served in a place where people were detained...?', 'q9_9', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '10.a. Were you EVER a part of any group, or did you EVER help any group, unit, or organization that used a weapon against any person, or threatened to do so?', 'q9_10a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '10.b. If you answered "Yes" to Item Number 10.a., when you were part of this group... did you ever use a weapon against another person?', 'q9_10b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '10.c. If you answered "Yes" to Item Number 10.a., when you were part of this group... did you ever threaten another person that you would use a weapon...?', 'q9_10c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '11. Have you EVER received any weapons training, paramilitary training, or other military-type training?', 'q9_11', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '12. Have you EVER sold, provided, or transported weapons, or assisted any person in selling, providing, or transporting weapons...?', 'q9_12', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '13. Have you EVER recruited (asked), enlisted (signed up), conscripted (required to join), or used any person under 15 years of age to serve in or help an armed group...?', 'q9_13', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '14. Have you EVER used any person under 15 years of age to take part in hostilities or attempted or worked with others to do so...?', 'q9_14', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '15.a. Have you EVER committed, agreed to commit, asked someone else to commit, helped commit, or tried to commit a crime or offense for which you were NOT arrested?', 'q9_15a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '15.b. Have you EVER been arrested, cited, detained or confined by any law enforcement officer, military official (in the U.S. or elsewhere), or immigration official for any reason, or been charged with a crime or offense?', 'q9_15b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answer "Yes" to any part of Item Number 15. above, complete the table with each crime or offense even if your records have been sealed, expunged, or otherwise cleared...', 'headingCrimeTableNote', 'heading');
        
        $this->addQ($sec9, '16. If you received a suspended sentence, were placed on probation, or were paroled, have you completed your suspended sentence, probation, or parole?', 'q9_16', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, 'Have you EVER:', 'headingHaveYouEver', 'heading');
        $this->addQ($sec9, '17.a. Engaged in prostitution, attempted to procure or import prostitutes...?', 'q9_17a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17.b. Manufactured, cultivated, produced, distributed, dispensed, sold, or smuggled (trafficked) any controlled substances...?', 'q9_17b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17.c. Married someone in order to obtain an immigration benefit?', 'q9_17c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17.d. Been married to more than one person at the same time?', 'q9_17d', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17.e. Helped anyone to enter, or try to enter, the United States illegally?', 'q9_17e', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17.f. Gambled illegally or received income from illegal gambling?', 'q9_17f', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17.g. Failed to support your dependents (pay child support) or to pay alimony...?', 'q9_17g', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '17.h. Made any misrepresentation to obtain any public benefit in the United States?', 'q9_17h', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '18. Have you EVER given any U.S. Government officials any information or documentation that was false, fraudulent, or misleading?', 'q9_18', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '19. Have you EVER lied to any U.S. Government officials to gain entry or admission into the United States or to gain immigration benefits...?', 'q9_19', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answer "Yes" to any of the questions in Item Numbers 17.a. - 19., provide an explanation in the space provided in Part 14. Additional Information.', 'headingExplain17_19', 'heading');
        
        $this->addQ($sec9, '20. Have you EVER been placed in removal, rescission, or deportation proceedings?', 'q9_20', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '21. Have you EVER been removed or deported from the United States?', 'q9_21', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answer "Yes" to Item Numbers 20. - 21. below, provide an explanation in the space provided in Part 14. Additional Information...', 'headingExplain20_21', 'heading');
        
        $this->addQ($sec9, '22.a. Are you a male who lived in the United States at any time between your 18th and 26th birthdays?', 'q9_22a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '22.b. If you answered "Yes," to Item Number 22.a., did you register for the Selective Service?', 'q9_22b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '22.c. If you answered "Yes," to Item Number 22.b., provide information about your registration. Date Registered (mm/dd/yyyy)', 'q9_22c_date', 'date');
        $this->addQ($sec9, '22.c. Selective Service Number', 'q9_22c_number');
        $this->addQ($sec9, 'If you answered "No," to Item Number 22.b. see the Specific Instructions by Item Number, Part 9. Additional Information About You of the Instructions for more information.', 'headingSelectiveServiceNote', 'heading');
        
        $this->addQ($sec9, '23. Have you EVER left the United States to avoid being drafted in the U.S. armed forces?', 'q9_23', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '24. Have you EVER applied for any kind of exemption from military service in the U.S. armed forces?', 'q9_24', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answer "Yes" to Item Numbers 23. - 24., provide an explanation in the space provided in Part 14. Additional Information.', 'headingExplain23_24', 'heading');
        
        $this->addQ($sec9, '25. Have you EVER served in the U.S. armed forces?', 'q9_25', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answered "No" to Item Number 25., go to Item Number 30.a.', 'headingGoTo30a', 'heading');
        
        $this->addQ($sec9, '26.a. Are you currently a member of the U.S. armed forces?', 'q9_26a', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '26.b. If you answered "Yes" to Item Number 26.a., are you scheduled to deploy outside the United States, including to a vessel, within the next 3 months?', 'q9_26b', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '26.c. If you answered "Yes," to Item Number 26.a., are you currently stationed outside the United States?', 'q9_26c', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '26.d. If you answered "No" to Item Number 26.a., are you a former U.S. military service member who is currently residing outside of the U.S.?', 'q9_26d', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '27. Have you EVER been discharged from training or service in the U.S. armed forces because you were an alien?', 'q9_27', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '28. Have you EVER been court-martialed or have you received a discharge characterized as other than honorable, bad conduct, or dishonorable, while in the U.S. armed forces?', 'q9_28', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '29. Have you EVER deserted from the U.S. armed forces?', 'q9_29', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answer "Yes" to Item Numbers 27. - 29., provide an explanation in the space provided in Part 14. Additional Information.', 'headingExplain27_29', 'heading');
        
        $this->addQ($sec9, 'For Item Numbers 30.a. - 37. see Specific Instructions by Item Number, Part 9. Additional Information About You. If you answer "Yes" to Item Number 30.a., provide an explanation in the space provided in Part 14. Additional Information.', 'headingExplain30a', 'heading');
        $this->addQ($sec9, '30.a. Do you now have, or did you EVER have, a hereditary title or an order of nobility in any foreign country?', 'q9_30a', 'radio', ['Yes', 'No (skip to Item Number 31.)']);
        $this->addQ($sec9, '30.b. If you answered "Yes," to Item Number 30.a., are you willing to give up any inherited titles or orders of nobility, (list titles), that you have in a foreign country at your naturalization ceremony?', 'q9_30b', 'radio', ['Yes', 'No']);
        
        $this->addQ($sec9, '31. Do you support the Constitution and form of Government of the United States?', 'q9_31', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '32. Do you understand the full Oath of Allegiance to the United States (see Part 16. Oath of Allegiance)?', 'q9_32', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '33. Are you unable to take the Oath of Allegiance because of a physical or developmental disability or mental impairment? If you answer "Yes," skip Item Numbers 34. - 37. and see the Legal Guardian, Surrogate, or Designated Representative section in the Instructions.', 'q9_33', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, 'If you answer "No" to any question except Item Number 33., see the Oath of Allegiance section of the Instructions for more information.', 'headingOathNoNote', 'heading');
        $this->addQ($sec9, '34. Are you willing to take the full Oath of Allegiance to the United States?', 'q9_34', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '35. If the law requires it, are you willing to bear arms (carry weapons) on behalf of the United States?', 'q9_35', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '36. If the law requires it, are you willing to perform noncombatant services (do something that does not include fighting in a war) in the U.S. armed forces?', 'q9_36', 'radio', ['Yes', 'No']);
        $this->addQ($sec9, '37. If the law requires it, are you willing to perform work of national importance under civilian direction (do non-military work that the U.S. Government says is important to the country)?', 'q9_37', 'radio', ['Yes', 'No']);

        // Part 10. Request for a Fee Reduction
        $sec10 = $form->sections()->create(['title' => 'Part 10. Request for a Fee Reduction', 'order' => 10]);
        $this->addQ($sec10, 'For information about fees, fee waivers, and reduced fees, see Form G-1055, Fee Schedule, at www.uscis.gov/g-1055. To apply for a reduced fee, complete Item Numbers 1. - 5.b. If you are not eligible for a reduced fee, complete Item Number 1. and proceed to Part 11.', 'headingFeeReductionNote', 'heading');
        $this->addQ($sec10, '1. My household income is less than or equal to 400% of the Federal Poverty Guidelines (see Instructions for required documentation).', 'feeReductionEligibility', 'radio', ['No (skip to Part 11.)', 'Yes (complete Item Numbers 2. - 5.b.)']);
        $this->addQ($sec10, '2. Total household income', 'totalHouseholdIncome', 'number');
        $this->addQ($sec10, '3. My household size is:', 'householdSize', 'number');
        $this->addQ($sec10, '4. Total number of household members earning income including yourself:', 'householdEarningIncomeSize', 'number');
        $this->addQ($sec10, '5.a. I am the head of household.', 'isHeadOfHousehold', 'radio', ['Yes', 'No']);
        $this->addQ($sec10, '5.b. Name of head of household (if you selected "No" in Item Number 5.a.):', 'headOfHouseholdName');

        // Part 11. Applicant's Contact Information, Certification, and Signature
        $sec11 = $form->sections()->create(['title' => 'Part 11. Applicant\'s Contact Information, Certification, and Signature', 'order' => 11]);
        $this->addQ($sec11, 'Provide your daytime telephone number, mobile telephone number (if any), and email address (if any).', 'headingApplicantContactInfo', 'heading');
        $this->addQ($sec11, '1. Applicant\'s Daytime Telephone Number', 'applicantDaytimePhone');
        $this->addQ($sec11, '2. Applicant\'s Mobile Telephone Number (if any)', 'applicantMobilePhone');
        $this->addQ($sec11, '3. Applicant\'s Email Address (if any)', 'applicantEmailAddress');
        $this->addQ($sec11, 'Applicant\'s Certification and Signature', 'headingApplicantSignature', 'heading');
        $this->addQ($sec11, '4. Applicant\'s Signature (or signature of a legal guardian, surrogate, or designated representative, if applicable)', 'applicantSignature');
        $this->addQ($sec11, '4. Date of Signature (mm/dd/yyyy)', 'applicantSignatureDate', 'date');

        // Part 12. Interpreter's Contact Information, Certification, and Signature
        $sec12 = $form->sections()->create(['title' => 'Part 12. Interpreter\'s Contact Information, Certification, and Signature', 'order' => 12]);
        $this->addQ($sec12, 'Interpreter\'s Full Name', 'headingInterpreterFullName', 'heading');
        $this->addQ($sec12, '1. Interpreter\'s Family Name (Last Name)', 'interpreterLastName');
        $this->addQ($sec12, '1. Interpreter\'s Given Name (First Name)', 'interpreterFirstName');
        $this->addQ($sec12, '2. Interpreter\'s Business or Organization Name', 'interpreterBusiness');
        $this->addQ($sec12, 'Interpreter\'s Contact Information', 'headingInterpreterContact', 'heading');
        $this->addQ($sec12, '3. Interpreter\'s Daytime Telephone Number', 'interpreterDaytimePhone');
        $this->addQ($sec12, '4. Interpreter\'s Mobile Telephone Number (if any)', 'interpreterMobilePhone');
        $this->addQ($sec12, '5. Interpreter\'s Email Address (if any)', 'interpreterEmailAddress');
        $this->addQ($sec12, 'Interpreter\'s Certification and Signature', 'headingInterpreterSignature', 'heading');
        $this->addQ($sec12, 'I certify, under penalty of perjury, that I am fluent in English and:', 'interpreterLanguage');
        $this->addQ($sec12, '6. Interpreter\'s Signature', 'interpreterSignature');
        $this->addQ($sec12, '6. Date of Signature (mm/dd/yyyy)', 'interpreterSignatureDate', 'date');

        // Part 13. Contact Information, Certification, and Signature of the Person Preparing this Application
        $sec13 = $form->sections()->create(['title' => 'Part 13. Contact Information, Certification, and Signature of the Person Preparing this Application, if Other Than the Applicant', 'order' => 13]);
        $this->addQ($sec13, 'Preparer\'s Full Name', 'headingPreparerFullName', 'heading');
        $this->addQ($sec13, '1. Preparer\'s Family Name (Last Name)', 'preparerLastName');
        $this->addQ($sec13, '1. Preparer\'s Given Name (First Name)', 'preparerFirstName');
        $this->addQ($sec13, '2. Preparer\'s Business or Organization Name', 'preparerBusiness');
        $this->addQ($sec13, 'Preparer\'s Contact Information', 'headingPreparerContact', 'heading');
        $this->addQ($sec13, '3. Preparer\'s Daytime Telephone Number', 'preparerDaytimePhone');
        $this->addQ($sec13, '4. Preparer\'s Mobile Telephone Number (if any)', 'preparerMobilePhone');
        $this->addQ($sec13, '5. Preparer\'s Email Address (if any)', 'preparerEmailAddress');
        $this->addQ($sec13, 'Preparer\'s Certification and Signature', 'headingPreparerSignature', 'heading');
        $this->addQ($sec13, '6. Preparer\'s Signature', 'preparerSignature');
        $this->addQ($sec13, '6. Date of Signature (mm/dd/yyyy)', 'preparerSignatureDate', 'date');

        // Part 14. Additional Information
        $sec14 = $form->sections()->create(['title' => 'Part 14. Additional Information', 'order' => 14]);
        $this->addQ($sec14, '1. Family Name (Last Name)', 'additionalLastName');
        $this->addQ($sec14, '1. Given Name (First Name)', 'additionalFirstName');
        $this->addQ($sec14, '1. Middle (if applicable)', 'additionalMiddleName');
        
        $this->addQ($sec14, '2. Page Number', 'additional1Page');
        $this->addQ($sec14, '2. Part Number', 'additional1Part');
        $this->addQ($sec14, '2. Item Number', 'additional1Item');
        $this->addQ($sec14, 'Additional Information 1', 'additional1Info', 'textarea');

        // Part 15 and 16
        $sec15 = $form->sections()->create(['title' => 'Part 15 and 16. Signature at Interview and Oath of Allegiance', 'order' => 15]);
        $this->addQ($sec15, 'Do not complete Parts 15. or 16. until the USCIS officer instructs you to do so at the interview.', 'headingDoNotComplete', 'heading');
        $this->addQ($sec15, 'Applicant\'s Signature at Interview', 'interviewApplicantSignature');
        $this->addQ($sec15, 'Date of Signature (mm/dd/yyyy)', 'interviewApplicantDate', 'date');
        $this->addQ($sec15, 'Oath of Allegiance: Applicant\'s Signature', 'oathApplicantSignature');
        $this->addQ($sec15, 'Oath of Allegiance: Date of Signature (mm/dd/yyyy)', 'oathApplicantDate', 'date');

        echo "Successfully seeded N-400 form!\n";
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