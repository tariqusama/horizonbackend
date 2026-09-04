<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateChildAbroadChecklistSeeder extends Seeder
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
                        "hint" => "Copy of birth certificate, U.S. passport biographic page, Certificate of Naturalization/Citizenship, OR front/back of Permanent Resident Card."
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
                        "name" => "Child's birth certificate",
                        "required" => true,
                        "hint" => "Must show the petitioning parent's name. Include a certified English translation if needed."
                    ],
                    [
                        "name" => "Final adoption decree + proof of custody (If adopted)",
                        "required" => false,
                        "hint" => "Plus proof of legal custody and residency before age 16."
                    ],
                    [
                        "name" => "Marriage certificate of parent and stepparent (If stepchild)",
                        "required" => false,
                        "hint" => "Plus proof of termination of any prior marriages."
                    ],
                    [
                        "name" => "Proof of legitimation or financial/emotional relationship (If father of child born out of wedlock)",
                        "required" => false,
                        "hint" => "Evidence of emotional/financial relationship before the child turned 21 or married."
                    ],
                    [
                        "name" => "Paternal or Maternal DNA test result",
                        "required" => false,
                        "hint" => "From an approved/accredited USCIS laboratory (if required to prove biological relationship)."
                    ]
                ]
            ],
            [
                "title" => "Secondary Proof of Relationship - Select At Least FIVE (5) (Only if Primary is unavailable)",
                "documents" => [
                    [
                        "name" => "Medical records or health records",
                        "required" => false,
                        "hint" => "Showing parent and child's names."
                    ],
                    [
                        "name" => "Church records or religious documents",
                        "required" => false,
                        "hint" => "Listing parent and child's names."
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
                        "name" => "Financial records (Tax returns)",
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
                    ],
                    [
                        "name" => "Proof of ongoing relationship",
                        "required" => false,
                        "hint" => "Money transfers/remittances for financial support, call logs, etc."
                    ]
                ]
            ],
            [
                "title" => "Beneficiary (Child) Documents",
                "documents" => [
                    [
                        "name" => "Passport biographic page",
                        "required" => true,
                        "hint" => "Upload a clear scan of the photo/info page of the child's valid passport."
                    ],
                    [
                        "name" => "Name change documents (Parent or Child)",
                        "required" => false,
                        "hint" => "Marriage certificate, divorce decree, adoption decree, or court order for any name changes (if applicable)."
                    ]
                ]
            ],
            [
                "title" => "National Visa Center (NVC) Stage Documents (Required AFTER I-130 Approval)",
                "documents" => [
                    [
                        "name" => "Form I-864 (Affidavit of Support) OR I-864W (Exemption)",
                        "required" => false,
                        "hint" => "Financial support docs. (Note: biological/adopted children under 18 of US citizens are often exempt and use I-864W)."
                    ],
                    [
                        "name" => "Child's Police Certificates",
                        "required" => false,
                        "hint" => "Required ONLY if the child is 16 years of age or older, from their country of residence."
                    ],
                    [
                        "name" => "Medical Examination",
                        "required" => false,
                        "hint" => "Must be completed by an embassy-approved panel physician prior to the consular interview."
                    ]
                ]
            ]
        ];

        DB::table('checklists')
            ->where('key', 'child_abroad')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The Child Abroad Checklist was successfully updated!');
    }
}
