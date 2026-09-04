<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDACAChecklistSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                "title" => "Proof of Identity and DACA Status",
                "documents" => [
                    [
                        "name" => "Copy of current Employment Authorization Document (EAD)",
                        "required" => true,
                        "hint" => "Upload a clear scan of the front and back of your current or most recent DACA work permit."
                    ],
                    [
                        "name" => "Government-issued photo ID",
                        "required" => true,
                        "hint" => "Passport, driver's license, state ID, etc."
                    ],
                    [
                        "name" => "Two (2) passport-style photos",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing the application (2x2 inches, white background). Required for the I-765 form."
                    ]
                ]
            ],
            [
                "title" => "Proof of Continuous Residence",
                "documents" => [
                    [
                        "name" => "Updated school, employment, or medical records",
                        "required" => false,
                        "hint" => "Showing you have continuously resided in the U.S. since your last DACA approval."
                    ],
                    [
                        "name" => "Updated rent, bills, or bank statements",
                        "required" => false,
                        "hint" => "Showing you have continuously resided in the U.S. since your last DACA approval."
                    ]
                ]
            ],
            [
                "title" => "Criminal / Immigration Records (If applicable)",
                "documents" => [
                    [
                        "name" => "Certified court dispositions for any new arrests, charges, or convictions",
                        "required" => false,
                        "hint" => "If you have been arrested or convicted of any crime SINCE your last DACA approval, you must submit certified court dispositions."
                    ],
                    [
                        "name" => "Immigration documents",
                        "required" => false,
                        "hint" => "If you had new removal proceedings, filings, or immigration notices since your last approval."
                    ]
                ]
            ],
            [
                "title" => "Travel Documents (If applicable)",
                "documents" => [
                    [
                        "name" => "Advance Parole document and Proof of Lawful Reentry",
                        "required" => false,
                        "hint" => "If you traveled outside the U.S. on Advance Parole since your last DACA approval, upload a copy of the Advance Parole document, your passport stamp, and your I-94 arrival record."
                    ]
                ]
            ]
        ];

        DB::table('checklists')
            ->where('key', 'daca')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The DACA (Renewal) Checklist was successfully updated!');
    }
}
