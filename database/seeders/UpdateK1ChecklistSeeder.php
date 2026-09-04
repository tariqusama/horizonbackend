<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateK1ChecklistSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                "title" => "Petitioner's (U.S. Citizen) Documents",
                "documents" => [
                    [
                        "name" => "Proof of U.S. citizenship",
                        "required" => true,
                        "hint" => "Copy of U.S. birth certificate, U.S. passport biographic page, Certificate of Naturalization, or Certificate of Citizenship."
                    ],
                    [
                        "name" => "Government-issued photo ID",
                        "required" => true,
                        "hint" => "Passport, driver's license, etc."
                    ],
                    [
                        "name" => "One (1) passport-style photo of the Petitioner",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing the petition (2x2 inches, white background)."
                    ]
                ]
            ],
            [
                "title" => "Beneficiary's (Foreign Fiancé(e)) Documents",
                "documents" => [
                    [
                        "name" => "Copy of passport biographic page",
                        "required" => true,
                        "hint" => "Upload a clear scan of the photo/info page of the beneficiary's valid passport."
                    ],
                    [
                        "name" => "Copy of birth certificate",
                        "required" => true,
                        "hint" => "Include a certified English translation if the document is not in English."
                    ],
                    [
                        "name" => "One (1) passport-style photo of the Beneficiary",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing the petition (2x2 inches, white background)."
                    ]
                ]
            ],
            [
                "title" => "Relationship Evidence",
                "documents" => [
                    [
                        "name" => "Evidence of in-person meeting within the last 2 years",
                        "required" => true,
                        "hint" => "Upload at least two pieces of evidence (boarding passes, flight itineraries, visas, entry/exit stamps, hotel bookings, event tickets, etc.). If you cannot meet due to extreme hardship or cultural customs, provide evidence for a Waiver Request."
                    ],
                    [
                        "name" => "Dated photos together",
                        "required" => true,
                        "hint" => "Photos must be labeled with names, places, and dates."
                    ],
                    [
                        "name" => "Communication evidence",
                        "required" => false,
                        "hint" => "Chat logs, emails, call records, letters, etc."
                    ],
                    [
                        "name" => "Proof of ongoing relationship",
                        "required" => false,
                        "hint" => "Money transferred receipts, shared accounts, etc."
                    ]
                ]
            ],
            [
                "title" => "Statements",
                "documents" => [
                    [
                        "name" => "Signed statements of intent to marry",
                        "required" => true,
                        "hint" => "Statements from both the petitioner and beneficiary declaring their intent to marry within 90 days of arrival in the U.S."
                    ]
                ]
            ],
            [
                "title" => "Prior Relationships & Background (If applicable)",
                "documents" => [
                    [
                        "name" => "Proof of termination of prior marriages",
                        "required" => false,
                        "hint" => "Divorce decree(s), annulment(s), or death certificate(s) for ALL prior marriages of both the petitioner and beneficiary."
                    ],
                    [
                        "name" => "Legal Name Change Documents",
                        "required" => false,
                        "hint" => "If either party has ever used a different name, provide the legal name change document (e.g., court order)."
                    ],
                    [
                        "name" => "Certified Criminal/Police Records (IMBRA)",
                        "required" => false,
                        "hint" => "If the U.S. citizen petitioner has ever been convicted of certain crimes (e.g., domestic violence, sexual assault), you must provide certified copies of all court and police records."
                    ]
                ]
            ]
        ];

        DB::table('checklists')
            ->where('key', 'k1_fiance')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The K-1 Fiancé(e) Checklist was successfully updated!');
    }
}
