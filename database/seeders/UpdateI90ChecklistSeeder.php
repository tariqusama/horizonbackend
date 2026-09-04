<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateI90ChecklistSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                "title" => "General Required Documents",
                "documents" => [
                    [
                        "name" => "Government-issued photo ID (passport, driver's license, etc)",
                        "required" => true,
                        "hint" => "Upload a valid state driver's license, state ID card, or passport showing your full legal name, date of birth, and photo."
                    ],
                    [
                        "name" => "Two (2) passport-style photos",
                        "required" => true,
                        "hint" => "Upload two recent (within 6 months) passport-style photos — front-facing, white background, 2x2 inches."
                    ]
                ]
            ],
            [
                "title" => "Additional Documents (Based on your Situation - Select the ones that apply to you)",
                "documents" => [
                    [
                        "name" => "[Lost, Stolen, or Destroyed] Copy of Green Card & Form I-797/Visa",
                        "required" => false,
                        "hint" => "Copy of the lost/stolen card (if available) AND a copy of Form I-797 (Notice of Action) or your Immigrant Visa."
                    ],
                    [
                        "name" => "[Mutilated or Damaged Card] Original Damaged Green Card",
                        "required" => false,
                        "hint" => "USCIS requires the ORIGINAL damaged card to be mailed."
                    ],
                    [
                        "name" => "[Card Expired or Expiring Soon] Copy of expiring/expired Green Card",
                        "required" => false,
                        "hint" => "Provide a copy of the front and back of the expiring or expired card."
                    ],
                    [
                        "name" => "[Card Issued but Never Received] Copy of Form I-797 & Proof of Mailing Address",
                        "required" => false,
                        "hint" => "Copy of Form I-797 (Notice of Action showing approval) and proof of your mailing address."
                    ],
                    [
                        "name" => "[Incorrect Info - USCIS Error] Original Incorrect Green Card",
                        "required" => false,
                        "hint" => "USCIS requires the ORIGINAL card containing the incorrect information."
                    ],
                    [
                        "name" => "[Incorrect Info - Applicant Error] Copy of Green Card & Evidence of Correct Info",
                        "required" => false,
                        "hint" => "Copy of your Green Card along with legal evidence of the correct information (birth certificate, passport, court order, etc.)."
                    ],
                    [
                        "name" => "[Biographic Info Changed] Original Green Card & Legal Document for Change",
                        "required" => false,
                        "hint" => "Submit the ORIGINAL Green Card and the legal document for the change (e.g., marriage certificate, divorce decree, court order, medical certification for gender)."
                    ],
                    [
                        "name" => "[Turning 14 Years Old] Copy of Green Card issued before age 14",
                        "required" => false,
                        "hint" => "Provide a copy of the front and back of your current card."
                    ],
                    [
                        "name" => "[Commuter Status] Proof of U.S. Employment & Foreign Residence",
                        "required" => false,
                        "hint" => "Evidence of your employment in the U.S. (e.g., pay stubs) and evidence of your residence in Canada or Mexico."
                    ],
                    [
                        "name" => "[Older Edition Card] Copy of older edition card (AR-3, AR-103, I-151)",
                        "required" => false,
                        "hint" => "Copy of the front and back of the older edition Alien Registration Receipt Card."
                    ]
                ]
            ]
        ];

        // This uses Laravel's DB facade so it automatically uses your live database credentials
        DB::table('checklists')
            ->where('key', 'i90')
            ->update(['sections' => $sections]); // Laravel automatically handles JSON encoding

        $this->command->info('SUCCESS: The I-90 Checklist was successfully updated!');
    }
}
