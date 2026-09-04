<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateI751ChecklistSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                "title" => "Proof of Conditional Green Card",
                "documents" => [
                    [
                        "name" => "Copy of Conditional Green Card (Front and Back)",
                        "required" => true,
                        "hint" => "Upload a clear scan of the front and back of your 2-year conditional Permanent Resident Card."
                    ],
                    [
                        "name" => "Government-issued photo ID",
                        "required" => true,
                        "hint" => "Passport, driver's license, etc."
                    ],
                    [
                        "name" => "Copies of Conditional Green Cards for dependent children (if applicable)",
                        "required" => false,
                        "hint" => "If you are including dependent children who received their conditional status at the same time or within 90 days of you, include copies of their green cards."
                    ]
                ]
            ],
            [
                "title" => "Marriage & Relationship Evidence (From date of marriage to present)",
                "documents" => [
                    [
                        "name" => "Marriage certificate",
                        "required" => true,
                        "hint" => "Original or certified copy. Include a certified translation if not in English."
                    ],
                    [
                        "name" => "Proof of termination of all prior marriages",
                        "required" => false,
                        "hint" => "Divorce decree(s), annulment(s), or death certificate(s) (if applicable)."
                    ],
                    [
                        "name" => "Photos together over time",
                        "required" => true,
                        "hint" => "Photos from family events, holidays, and trips with captions/descriptions and dates."
                    ]
                ]
            ],
            [
                "title" => "Joint Marriage Evidence - Select At Least EIGHT (8)",
                "documents" => [
                    [
                        "name" => "Birth certificates of children born to the marriage",
                        "required" => false,
                        "hint" => "If any children were born to you and your spouse."
                    ],
                    [
                        "name" => "Joint lease/mortgage or property documents",
                        "required" => false,
                        "hint" => "Showing both names."
                    ],
                    [
                        "name" => "Proof of Name Change with Social Security and/or MVA",
                        "required" => false,
                        "hint" => "Evidence you updated your legal name after marriage."
                    ],
                    [
                        "name" => "Joint bank account statements",
                        "required" => false,
                        "hint" => "Spanning from the time of marriage/green card approval to present."
                    ],
                    [
                        "name" => "Joint federal and state tax returns",
                        "required" => false,
                        "hint" => "IRS transcripts or copies filed as 'Married Filing Jointly'."
                    ],
                    [
                        "name" => "Joint loan and/or credit card statements",
                        "required" => false,
                        "hint" => "Showing both spouses as account holders."
                    ],
                    [
                        "name" => "Insurance policies naming each other",
                        "required" => false,
                        "hint" => "Health, auto, or life insurance listing the spouse as a beneficiary or dependent."
                    ],
                    [
                        "name" => "Utility bills showing both names",
                        "required" => false,
                        "hint" => "Gas, electricity, water, internet bills to the same shared address."
                    ],
                    [
                        "name" => "Signed & Notarized Affidavits from family/friends",
                        "required" => false,
                        "hint" => "At least 2 letters confirming the relationship from people who have known you since you were granted conditional residence."
                    ],
                    [
                        "name" => "Travel records",
                        "required" => false,
                        "hint" => "Airline tickets, hotel reservations, or passport stamps showing joint travel."
                    ],
                    [
                        "name" => "Adoption records of children",
                        "required" => false,
                        "hint" => "If you jointly adopted children during the marriage."
                    ],
                    [
                        "name" => "Vehicle registrations in both names",
                        "required" => false,
                        "hint" => "Showing joint ownership of vehicles."
                    ]
                ]
            ],
            [
                "title" => "Background & Special Circumstances (If applicable)",
                "documents" => [
                    [
                        "name" => "Written Explanation for Late Filing",
                        "required" => false,
                        "hint" => "If you are filing this form after your conditional green card has already expired (or outside the 90-day window), you must provide a written explanation showing good cause for the delay."
                    ],
                    [
                        "name" => "Certified Police & Court Records",
                        "required" => false,
                        "hint" => "If the conditional resident has ever been arrested, cited, or charged with a crime since becoming a permanent resident, you must provide certified police and court records."
                    ]
                ]
            ]
        ];

        DB::table('checklists')
            ->where('key', 'i751')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The I-751 (Removal of Conditions) Checklist was successfully updated!');
    }
}
