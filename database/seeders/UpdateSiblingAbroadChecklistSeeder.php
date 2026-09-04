<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSiblingAbroadChecklistSeeder extends Seeder
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
                        "hint" => "Copy of birth certificate, U.S. passport biographic page, or Certificate of Naturalization/Citizenship. (Note: You MUST be at least 21 years old to petition for a sibling)."
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
                        "name" => "Petitioner's birth certificate",
                        "required" => true,
                        "hint" => "Must show your parents' names."
                    ],
                    [
                        "name" => "Sibling's birth certificate",
                        "required" => true,
                        "hint" => "Must show at least one common parent. Include certified English translation if needed."
                    ],
                    [
                        "name" => "Proof of termination of parents' prior marriages (If half-siblings)",
                        "required" => false,
                        "hint" => "Divorce decrees, annulments, or death certificates."
                    ],
                    [
                        "name" => "Adoption decrees + proof of custody (If adopted siblings)",
                        "required" => false,
                        "hint" => "Proof both were adopted by the same parent(s) before age 16."
                    ],
                    [
                        "name" => "Marriage certificate of parent to stepparent (If step-siblings)",
                        "required" => false,
                        "hint" => "Plus proof of termination of prior marriages. The relationship must have been formed before age 18."
                    ]
                ]
            ],
            [
                "title" => "Secondary Proof of Relationship - Select At Least FOUR (4) (Only if Primary is unavailable)",
                "documents" => [
                    [
                        "name" => "Sibling DNA test result",
                        "required" => false,
                        "hint" => "From an approved/accredited USCIS laboratory."
                    ],
                    [
                        "name" => "Medical records or health records",
                        "required" => false,
                        "hint" => "Showing parent and siblings' names."
                    ],
                    [
                        "name" => "Church records or religious documents",
                        "required" => false,
                        "hint" => "Listing parent and siblings' names."
                    ],
                    [
                        "name" => "Insurance records (Health or Life)",
                        "required" => false,
                        "hint" => "Listing the names of both siblings."
                    ],
                    [
                        "name" => "Employment records",
                        "required" => false,
                        "hint" => "Showing both sibling names."
                    ],
                    [
                        "name" => "Financial records",
                        "required" => false,
                        "hint" => "Listing both siblings' names."
                    ],
                    [
                        "name" => "Census or tribal records",
                        "required" => false,
                        "hint" => "Showing names."
                    ],
                    [
                        "name" => "Proof of ongoing sibling relationship",
                        "required" => false,
                        "hint" => "Money transfers/remittances for financial support, call logs, photos together, etc."
                    ]
                ]
            ],
            [
                "title" => "Beneficiary (Sibling) Documents",
                "documents" => [
                    [
                        "name" => "Passport biographic page",
                        "required" => true,
                        "hint" => "Upload a clear scan of the photo/info page of the sibling's valid passport."
                    ],
                    [
                        "name" => "Name change documents (Optional)",
                        "required" => false,
                        "hint" => "Marriage certificate, divorce decree, or court order for any legal name changes for the petitioner or beneficiary."
                    ]
                ]
            ],
            [
                "title" => "National Visa Center (NVC) Stage Documents (Required AFTER I-130 Approval)",
                "documents" => [
                    [
                        "name" => "Form I-864 (Affidavit of Support) & Financial Documents",
                        "required" => false,
                        "hint" => "Note: Sibling petitions (F4 category) have a long wait time. You will submit this years later when a visa becomes available."
                    ],
                    [
                        "name" => "Sibling's Police Certificates",
                        "required" => false,
                        "hint" => "Required from their country of residence and any country they lived in for more than 6 months after age 16 (submitted later at NVC stage)."
                    ],
                    [
                        "name" => "Medical Examination",
                        "required" => false,
                        "hint" => "Must be completed by an embassy-approved panel physician prior to the consular interview (years later)."
                    ]
                ]
            ]
        ];

        DB::table('checklists')
            ->where('key', 'sibling_abroad')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The Sibling Abroad Checklist was successfully updated!');
    }
}
