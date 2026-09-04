<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSpouseAOSChecklistSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                "title" => "Petitioner's (U.S. Citizen or Green Card Holder) Documents",
                "documents" => [
                    [
                        "name" => "Proof of U.S. citizenship OR Copy of Green Card",
                        "required" => true,
                        "hint" => "U.S. citizens: copy of birth certificate, U.S. passport biographic page, or Certificate of Naturalization/Citizenship. Permanent residents: copy of Green Card (front and back)."
                    ],
                    [
                        "name" => "Government-issued photo ID",
                        "required" => true,
                        "hint" => "Passport, driver's license, etc."
                    ],
                    [
                        "name" => "Two (2) passport-style photos of the Petitioner",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing the petition (2x2 inches, white background)."
                    ]
                ]
            ],
            [
                "title" => "Beneficiary's (Immigrant Spouse) Documents",
                "documents" => [
                    [
                        "name" => "Birth Certificate",
                        "required" => true,
                        "hint" => "Include a certified English translation if it is not in English."
                    ],
                    [
                        "name" => "Passport biographic page",
                        "required" => true,
                        "hint" => "Upload a clear scan of the photo/info page of the beneficiary's valid passport."
                    ],
                    [
                        "name" => "Form I-94 Arrival/Departure Record",
                        "required" => true,
                        "hint" => "Printout of the most recent I-94 record from the CBP website."
                    ],
                    [
                        "name" => "Proof of lawful entry",
                        "required" => true,
                        "hint" => "Copy of visa, admission stamp, or parole documents."
                    ],
                    [
                        "name" => "Eight (8) passport-style photos of the Beneficiary",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing. (2 for I-130, 2 for I-485, 2 for I-765, 2 for I-131)."
                    ]
                ]
            ],
            [
                "title" => "Medical & Background Documents (Beneficiary)",
                "documents" => [
                    [
                        "name" => "Form I-693 (Medical Examination and Vaccination Record)",
                        "required" => true,
                        "hint" => "A sealed medical examination completed by a USCIS-designated civil surgeon."
                    ],
                    [
                        "name" => "Certified Police & Court Records",
                        "required" => false,
                        "hint" => "Only required if the beneficiary has ever been arrested, cited, or charged with a crime in any country."
                    ],
                    [
                        "name" => "J-1 Visa Waiver (Form I-612 Approval Notice)",
                        "required" => false,
                        "hint" => "Only required if the beneficiary was previously on a J-1/J-2 visa subject to the 2-year home-country residency requirement."
                    ]
                ]
            ],
            [
                "title" => "Marriage & Relationship Evidence",
                "documents" => [
                    [
                        "name" => "Marriage certificate",
                        "required" => true,
                        "hint" => "Original or certified copy. Include a certified translation if not in English."
                    ],
                    [
                        "name" => "Proof of termination of all prior marriages",
                        "required" => false,
                        "hint" => "Divorce decree(s), annulment(s), or death certificate(s) for ALL prior marriages of both spouses (if applicable)."
                    ],
                    [
                        "name" => "Photos together over time",
                        "required" => true,
                        "hint" => "Photos with family/friends, labeled with dates and locations."
                    ],
                    [
                        "name" => "Birth certificates of children born to the marriage",
                        "required" => false,
                        "hint" => "Listing both spouses as parents (if any)."
                    ]
                ]
            ],
            [
                "title" => "Joint Marriage Evidence - Select At Least EIGHT (8)",
                "documents" => [
                    [
                        "name" => "Joint lease/mortgage or property documents",
                        "required" => false,
                        "hint" => "Must show both spouses' names."
                    ],
                    [
                        "name" => "Joint Bank Account Statements",
                        "required" => false,
                        "hint" => "Monthly or quarterly statements showing both names."
                    ],
                    [
                        "name" => "Joint Tax Returns",
                        "required" => false,
                        "hint" => "IRS transcripts, or copies filed as 'Married Filing Jointly'."
                    ],
                    [
                        "name" => "Shared Loans or Debts",
                        "required" => false,
                        "hint" => "Car loans, student loans, or personal loans with both names."
                    ],
                    [
                        "name" => "Wedding Souvenir / Invitation",
                        "required" => false,
                        "hint" => "Programs, invites, or souvenirs from the wedding ceremony."
                    ],
                    [
                        "name" => "Wedding rings and/or venue booking receipts",
                        "required" => false,
                        "hint" => "Photos of rings or receipts from wedding vendors."
                    ],
                    [
                        "name" => "Insurance policies naming each other",
                        "required" => false,
                        "hint" => "Health, auto, or life insurance listing the spouse as a beneficiary or co-insured."
                    ],
                    [
                        "name" => "Utility bills showing both names",
                        "required" => false,
                        "hint" => "Gas, electricity, water, internet bills to the same shared address."
                    ],
                    [
                        "name" => "Signed & Notarized Affidavits from family/friends",
                        "required" => false,
                        "hint" => "At least 2 letters confirming the relationship from people who know you as a couple."
                    ],
                    [
                        "name" => "Travel Records",
                        "required" => false,
                        "hint" => "Flight itineraries, boarding passes, or hotel bookings showing joint travel."
                    ],
                    [
                        "name" => "Social Media Evidence",
                        "required" => false,
                        "hint" => "Posts, comments, or tagged photos showing the relationship publicly."
                    ],
                    [
                        "name" => "Correspondence",
                        "required" => false,
                        "hint" => "Emails, chats, SMS, or call records showing ongoing communication."
                    ]
                ]
            ],
            [
                "title" => "Petitioner's Financial Affidavit Documents (I-864)",
                "documents" => [
                    [
                        "name" => "Most recent three (3) years IRS tax return, transcript, or Form 1040 with W-2s",
                        "required" => true,
                        "hint" => "Federal income tax returns for the petitioner."
                    ],
                    [
                        "name" => "Employment verification letter and/or 6 months' pay stubs OR proof of self-employment",
                        "required" => true,
                        "hint" => "Current proof of income."
                    ],
                    [
                        "name" => "Six (6) Months Bank Statements",
                        "required" => false,
                        "hint" => "Optional but recommended."
                    ],
                    [
                        "name" => "Evidence of assets",
                        "required" => false,
                        "hint" => "Only needed if income is insufficient to meet poverty guidelines."
                    ]
                ]
            ],
            [
                "title" => "Joint Sponsor Required Documents (If Applicable)",
                "documents" => [
                    [
                        "name" => "Proof of U.S. citizenship OR Copy of Green Card",
                        "required" => false,
                        "hint" => "For the Joint Sponsor."
                    ],
                    [
                        "name" => "Government-issued photo ID",
                        "required" => false,
                        "hint" => "For the Joint Sponsor."
                    ],
                    [
                        "name" => "Most recent three (3) years IRS tax return (Form 1040) OR transcript",
                        "required" => false,
                        "hint" => "For the Joint Sponsor."
                    ],
                    [
                        "name" => "1099s and/or W-2s for the most recent tax year",
                        "required" => false,
                        "hint" => "For the Joint Sponsor."
                    ],
                    [
                        "name" => "Employment verification letter and/or 6 months' pay stubs OR proof of self-employment",
                        "required" => false,
                        "hint" => "For the Joint Sponsor."
                    ]
                ]
            ],
            [
                "title" => "Household Member's Income Required Documents (If Applicable)",
                "documents" => [
                    [
                        "name" => "Proof of U.S. citizenship OR Copy of Green Card",
                        "required" => false,
                        "hint" => "For the Household Member."
                    ],
                    [
                        "name" => "Government-issued photo ID",
                        "required" => false,
                        "hint" => "For the Household Member."
                    ],
                    [
                        "name" => "Most recent three (3) years IRS tax return (Form 1040) OR transcript",
                        "required" => false,
                        "hint" => "For the Household Member."
                    ],
                    [
                        "name" => "1099s and/or W-2s for the most recent tax year",
                        "required" => false,
                        "hint" => "For the Household Member."
                    ],
                    [
                        "name" => "Employment verification letter and/or 6 months' pay stubs OR proof of self-employment",
                        "required" => false,
                        "hint" => "For the Household Member."
                    ]
                ]
            ]
        ];

        DB::table('checklists')
            ->where('key', 'spouse_aos')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The Marriage-Based AOS Checklist was successfully updated!');
    }
}
