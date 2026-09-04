<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateN400ChecklistSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                "title" => "Identity Documents",
                "documents" => [
                    [
                        "name" => "Copy of valid Permanent Resident Card (Green Card)",
                        "required" => true,
                        "hint" => "Upload a clear scan of the front and back of your Green Card."
                    ],
                    [
                        "name" => "State-issued ID",
                        "required" => true,
                        "hint" => "Driver's license or state ID."
                    ],
                    [
                        "name" => "Two (2) Passport-Style Photos",
                        "required" => false,
                        "hint" => "Only required if you currently reside OUTSIDE the United States."
                    ]
                ]
            ],
            [
                "title" => "Biographic & Civil Documents",
                "documents" => [
                    [
                        "name" => "Birth certificate",
                        "required" => false,
                        "hint" => "If needed for special cases."
                    ],
                    [
                        "name" => "Marriage certificate(s)",
                        "required" => false,
                        "hint" => "If currently married."
                    ],
                    [
                        "name" => "Divorce decrees / annulment papers / death certificates",
                        "required" => false,
                        "hint" => "For any prior marriages."
                    ],
                    [
                        "name" => "Name change documents",
                        "required" => false,
                        "hint" => "If you legally changed your name (e.g., court order)."
                    ]
                ]
            ],
            [
                "title" => "Proof of Residence & Eligibility",
                "documents" => [
                    [
                        "name" => "Proof of continuous residence & physical presence",
                        "required" => true,
                        "hint" => "Leases, mortgages, utility bills, employment records."
                    ],
                    [
                        "name" => "Certified tax transcripts or returns",
                        "required" => false,
                        "hint" => "Especially critical for marriage-based cases, self-employed applicants, or if you had tax issues."
                    ],
                    [
                        "name" => "Selective Service registration proof",
                        "required" => false,
                        "hint" => "If male, age 18-26 when required to register."
                    ]
                ]
            ],
            [
                "title" => "Family-Related Evidence (if applying under 3-year marriage rule)",
                "documents" => [
                    [
                        "name" => "Proof of spouse's U.S. citizenship",
                        "required" => false,
                        "hint" => "Birth certificate, naturalization certificate, or U.S. passport."
                    ],
                    [
                        "name" => "Proof of ongoing marital union",
                        "required" => false,
                        "hint" => "Joint bank accounts, leases, children's birth certificates, joint taxes, etc."
                    ]
                ]
            ],
            [
                "title" => "Background, Military & Special Circumstances (If applicable)",
                "documents" => [
                    [
                        "name" => "Certified Police & Court Records",
                        "required" => false,
                        "hint" => "If you have ever been arrested, cited, detained, or charged with a crime anywhere in the world, you MUST provide certified court and police records to prove Good Moral Character."
                    ],
                    [
                        "name" => "Proof of Child Support Payments",
                        "required" => false,
                        "hint" => "If you have dependent children living apart from you, you must provide court/support orders and evidence of payment to prove Good Moral Character."
                    ],
                    [
                        "name" => "Form N-648 (Medical Certification for Disability Exceptions)",
                        "required" => false,
                        "hint" => "If you are seeking an exception to the English/civics test due to a physical or developmental disability or mental impairment."
                    ],
                    [
                        "name" => "Form N-426 & Military Records",
                        "required" => false,
                        "hint" => "If applying based on military service (Request for Certification of Military/National Guard Service, DD Form 214, NGB Form 22, discharge papers)."
                    ]
                ]
            ]
        ];

        DB::table('checklists')
            ->where('key', 'n400')
            ->update(['sections' => $sections]);

        $this->command->info('SUCCESS: The N-400 (Naturalization) Checklist was successfully updated!');
    }
}
