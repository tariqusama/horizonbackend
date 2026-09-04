<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSpouseAbroadChecklistSeeder extends Seeder
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
                    ],
                    [
                        "name" => "Two (2) Passport-Style Photos of the Petitioner",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing. (Required for spouse petitions)."
                    ]
                ]
            ],
            [
                "title" => "Beneficiary (Spouse) Documents",
                "documents" => [
                    [
                        "name" => "Birth Certificate",
                        "required" => true,
                        "hint" => "Include a certified English translation if not in English."
                    ],
                    [
                        "name" => "Passport biographic page",
                        "required" => true,
                        "hint" => "Upload a clear scan of the photo/info page of the spouse's valid passport."
                    ],
                    [
                        "name" => "Two (2) Passport-Style Photos of the Beneficiary",
                        "required" => true,
                        "hint" => "Taken within 30 days of filing. (Required for spouse petitions)."
                    ]
                ]
            ],
            [
                "title" => "Marriage & Relationship Evidence (Primary)",
                "documents" => [
                    [
                        "name" => "Marriage certificate",
                        "required" => true,
                        "hint" => "Original or Certified Copy."
                    ],
                    [
                        "name" => "Proof of termination of all prior marriages",
                        "required" => false,
                        "hint" => "Divorce decree, annulment, or death certificate for BOTH the petitioner and beneficiary (if applicable)."
                    ],
                    [
                        "name" => "Photos together over time",
                        "required" => true,
                        "hint" => "With family/friends (labeled with dates/locations)."
                    ],
                    [
                        "name" => "Birth certificates of children born to the marriage",
                        "required" => false,
                        "hint" => "Listing both spouses as parents (if any)."
                    ]
                ]
            ],
            [
                "title" => "Additional Evidence of Bona Fide Marriage - Select At Least FIVE (5)",
                "documents" => [
                    [
                        "name" => "Proof of ongoing relationship / Joint Finances",
                        "required" => false,
                        "hint" => "Money transferred receipts, shared bank accounts, joint lease, etc."
                    ],
                    [
                        "name" => "Wedding Souvenirs / Invitations",
                        "required" => false,
                        "hint" => "Programs, invitations, etc."
                    ],
                    [
                        "name" => "Wedding rings and/or wedding venue booking receipts",
                        "required" => false,
                        "hint" => "Receipts showing joint expenses for the wedding."
                    ],
                    [
                        "name" => "Insurance policies naming each other",
                        "required" => false,
                        "hint" => "Health, life, or car insurance listing the spouse as a beneficiary or joint policyholder."
                    ],
                    [
                        "name" => "Signed and Notarized Affidavits from family/friends",
                        "required" => false,
                        "hint" => "Confirming the relationship (at least 2 letters)."
                    ],
                    [
                        "name" => "Travel Records",
                        "required" => false,
                        "hint" => "Flight itineraries, boarding passes, hotel bookings showing visits to each other."
                    ],
                    [
                        "name" => "Social Media Evidence",
                        "required" => false,
                        "hint" => "Posts, comments, tagged photos showing the relationship publicly."
                    ],
                    [
                        "name" => "Correspondence",
                        "required" => false,
                        "hint" => "Emails, chats, SMS, call logs showing ongoing communication."
                    ]
                ]
            ],
            [
                "title" => "Any other Additional Documents (Optional)",
                "documents" => [
                    [
                        "name" => "Name change documents",
                        "required" => false,
                        "hint" => "Marriage certificate, divorce decree, or court order for any legal name changes for either spouse (if applicable)."
                    ]
                ]
            ],
            [
                "title" => "National Visa Center (NVC) Stage Documents (Required AFTER I-130 Approval)",
                "documents" => [
                    [
                        "name" => "Form I-864 (Affidavit of Support) & Financial Documents",
                        "required" => false,
                        "hint" => "You will need to submit this along with your recent tax returns and W-2s once the case reaches the NVC."
                    ],
                    [
                        "name" => "Spouse's Police Certificates",
                        "required" => false,
                        "hint" => "Required from their country of residence and any country they lived in for more than 6 months after age 16."
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
            ->where('key', 'spouse_abroad')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The Spouse Abroad Checklist was successfully updated!');
    }
}
