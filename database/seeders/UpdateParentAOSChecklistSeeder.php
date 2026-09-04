<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateParentAOSChecklistSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                "title" => "Petitioner's (U.S. Citizen Child) Documents",
                "documents" => [
                    [
                        "name" => "Proof of U.S. citizenship",
                        "required" => true,
                        "hint" => "Copy of birth certificate, U.S. passport biographic page, or Certificate of Naturalization/Citizenship."
                    ],
                    [
                        "name" => "Government-issued photo ID",
                        "required" => true,
                        "hint" => "Passport, driver's license, etc."
                    ]
                ]
            ],
            [
                "title" => "Proof of Relationship (Primary)",
                "documents" => [
                    [
                        "name" => "U.S. citizen child's birth certificate",
                        "required" => true,
                        "hint" => "Must show the petitioning parent's name."
                    ],
                    [
                        "name" => "Final adoption decree + proof of custody (If adopted)",
                        "required" => false,
                        "hint" => "Plus proof of legal custody and residency before age 16."
                    ],
                    [
                        "name" => "Marriage certificate of parent (If step-parent)",
                        "required" => false,
                        "hint" => "Plus proof of termination of any prior marriages."
                    ],
                    [
                        "name" => "Proof of legitimation or financial/emotional relationship (If father of child born out of wedlock)",
                        "required" => false,
                        "hint" => "Evidence of emotional/financial relationship before the child turned 21 or married."
                    ]
                ]
            ],
            [
                "title" => "Secondary Proof of Relationship - Select At Least FIVE (5) (Only if Primary is unavailable)",
                "documents" => [
                    [
                        "name" => "Paternal or Maternal DNA test result",
                        "required" => false,
                        "hint" => "From an approved/accredited USCIS laboratory."
                    ],
                    [
                        "name" => "Medical records or health records",
                        "required" => false,
                        "hint" => "Showing parent and child's names."
                    ],
                    [
                        "name" => "Church records or religious documents",
                        "required" => false,
                        "hint" => "Listing parent and child's names (e.g. baptismal certificates)."
                    ],
                    [
                        "name" => "Insurance records",
                        "required" => false,
                        "hint" => "Naming both petitioner and beneficiary."
                    ],
                    [
                        "name" => "Employment records",
                        "required" => false,
                        "hint" => "Showing parent and child's names."
                    ],
                    [
                        "name" => "Financial records or Tax Returns",
                        "required" => false,
                        "hint" => "Listing parent and child's names."
                    ],
                    [
                        "name" => "Census or tribal records",
                        "required" => false,
                        "hint" => "In both names."
                    ],
                    [
                        "name" => "Government records or identification documents",
                        "required" => false,
                        "hint" => "Showing names of both petitioner and beneficiary."
                    ]
                ]
            ],
            [
                "title" => "Parent's Identity & Immigration Documents",
                "documents" => [
                    [
                        "name" => "Parent's birth certificate",
                        "required" => true,
                        "hint" => "Include a certified English translation if needed."
                    ],
                    [
                        "name" => "Passport biographic page",
                        "required" => true,
                        "hint" => "Upload a clear scan of the photo/info page of the parent's valid passport."
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
                        "name" => "Six (6) passport-style photos of the Parent",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing. (2 for I-485, 2 for I-765, 2 for I-131)."
                    ]
                ]
            ],
            [
                "title" => "Medical & Background Documents (Parent)",
                "documents" => [
                    [
                        "name" => "Form I-693 (Medical Examination and Vaccination Record)",
                        "required" => true,
                        "hint" => "A sealed medical examination completed by a USCIS-designated civil surgeon."
                    ],
                    [
                        "name" => "Certified Police & Court Records",
                        "required" => false,
                        "hint" => "Only required if the parent has ever been arrested, cited, or charged with a crime in any country."
                    ],
                    [
                        "name" => "Name change documents (Parent or Child)",
                        "required" => false,
                        "hint" => "Marriage certificate, divorce decree, adoption decree, or court order for any name changes (if applicable)."
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
            ->where('key', 'parent_aos')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The Parent AOS Checklist was successfully updated!');
    }
}
