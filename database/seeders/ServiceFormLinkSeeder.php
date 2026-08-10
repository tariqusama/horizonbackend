<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\DynamicForm;
use Illuminate\Support\Facades\DB;

class ServiceFormLinkSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate the pivot table to start fresh
        DB::table('dynamic_form_service')->truncate();

        // Get all forms
        $forms = DynamicForm::all()->keyBy('slug');

        $mappings = [
            // 1. NATURALIZATION (N-400)
            'Application for U.S. Citizenship (Naturalization / N-400)' => [
                'core' => ['n-400'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 2. ADJUSTMENT OF STATUS – SPOUSE
            'Marriage Green Card inside the U.S. – Concurrent Filing' => [
                'core' => ['i-130', 'i-130a', 'i-485', 'i-864'],
                'optional' => [
                    'g-1145' => 'wants_g1145',
                    'i-765' => 'wants_ead',
                    'i-131' => 'wants_ap',
                    'i-864-joint' => 'wants_joint_sponsor', // Note: Same form, maybe different slug or just duplicate entry?
                    'i-864a' => 'wants_household_member'
                ]
            ],
            // 3. ADJUSTMENT OF STATUS – PARENT
            'Parent Adjustment of Status inside the U.S. – Concurrent Filing' => [
                'core' => ['i-130', 'i-485', 'i-864'],
                'optional' => [
                    'g-1145' => 'wants_g1145',
                    'i-765' => 'wants_ead',
                    'i-131' => 'wants_ap',
                    'i-864-joint' => 'wants_joint_sponsor',
                    'i-864a' => 'wants_household_member'
                ]
            ],
            // 4. ADJUSTMENT OF STATUS – CHILD
            'Child Adjustment of Status inside the U.S. – Concurrent Filing' => [
                'core' => ['i-130', 'i-485', 'i-864'],
                'optional' => [
                    'g-1145' => 'wants_g1145',
                    'i-765' => 'wants_ead',
                    'i-131' => 'wants_ap',
                    'i-864-joint' => 'wants_joint_sponsor',
                    'i-864a' => 'wants_household_member'
                ]
            ],
            // 5. SPOUSE ABROAD
            'Petition for a Spouse outside the U.S. – USCIS Petition only' => [
                'core' => ['i-130', 'i-130a'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 6. PARENT ABROAD
            'Petition for a Parent outside the U.S. – USCIS Petition only' => [
                'core' => ['i-130'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 7. CHILD ABROAD
            'Petition for a Child outside the U.S. – USCIS Petition only' => [
                'core' => ['i-130'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 8. SIBLING
            'Petition for a Sibling outside the U.S. – USCIS Petition only' => [
                'core' => ['i-130'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 9. K-1 FIANCÉ(E)
            'K-1 Fiancé Visa – USCIS Petition only' => [
                'core' => ['i-129f'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 10. REMOVAL OF CONDITIONS
            'Petition to Remove Conditions on Conditional Residence – Joint Filing' => [
                'core' => ['i-751'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 11. GREEN CARD RENEWAL/REPLACEMENT
            'Renew or Replace Permanent Resident Card (Green Card Renewal / I-90)' => [
                'core' => ['i-90'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ],
            // 12. DACA RENEWAL
            'DACA Renewal (Deferred Action for Childhood Arrivals)' => [
                'core' => ['i-821d', 'i-765', 'i-765ws'],
                'optional' => [
                    'g-1145' => 'wants_g1145'
                ]
            ]
        ];

        foreach ($mappings as $serviceTitle => $config) {
            $service = Service::where('title', $serviceTitle)->first();
            if (!$service) {
                continue;
            }

            // Attach core forms
            foreach ($config['core'] as $formSlug) {
                if (isset($forms[$formSlug])) {
                    $service->dynamicForms()->attach($forms[$formSlug]->id, [
                        'is_required' => true,
                        'condition_code' => null
                    ]);
                }
            }

            // Attach optional forms
            foreach ($config['optional'] as $formSlug => $conditionCode) {
                // If special slugs like i-864-joint are used, we map them to the base form
                $actualSlug = str_replace('-joint', '', $formSlug);
                
                if (isset($forms[$actualSlug])) {
                    if (in_array($actualSlug, $config['core'])) {
                        continue;
                    }
                    $service->dynamicForms()->attach($forms[$actualSlug]->id, [
                        'is_required' => false,
                        'condition_code' => $conditionCode
                    ]);
                }
            }
        }
    }
}
