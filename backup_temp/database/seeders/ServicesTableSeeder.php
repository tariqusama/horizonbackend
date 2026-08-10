<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Adjustment of Status',
                'subtitle' => 'Apply for permanent residence while staying in the United States',
                'pill_text' => 'Adjustment of Status (Inside U.S.)',
                'order_index' => 1,
                'services' => [
                    [
                        'title' => 'Marriage Green Card inside the U.S. – Concurrent Filing',
                        'subtitle' => 'I-130 and I-485 concurrent filing for marriage-based green card',
                        'starting_price' => '$629.99',
                        'processing_time' => '12-18 months',
                        'is_popular' => true,
                        'order_index' => 1,
                        'requirements' => [
                            "Married to U.S. citizen or permanent resident",
                            "Lawful entry to the United States",
                            "No disqualifying criminal history"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 629.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 949.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 1249.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'Parent Adjustment of Status inside the U.S. – Concurrent Filing',
                        'subtitle' => 'I-130 and I-485 concurrent filing for parent adjustment',
                        'starting_price' => '$599.99',
                        'processing_time' => '10-16 months',
                        'is_popular' => false,
                        'order_index' => 2,
                        'requirements' => [
                            "Valid parent-child relationship",
                            "Currently in the United States",
                            "Financial sponsorship available"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 599.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 949.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 1249.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'Child Adjustment of Status inside the U.S. – Concurrent Filing',
                        'subtitle' => 'I-130 and I-485 concurrent filing for child adjustment',
                        'starting_price' => '$599.99',
                        'processing_time' => '10-16 months',
                        'is_popular' => false,
                        'order_index' => 3,
                        'requirements' => [
                            "Valid parent-child relationship",
                            "Currently in the United States",
                            "Financial sponsorship available"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 599.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 949.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 1249.99, 'order_index' => 3],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Family Petitions',
                'subtitle' => 'Petition for family members currently living outside the United States',
                'pill_text' => 'Family Petitions (Outside U.S.)',
                'order_index' => 2,
                'services' => [
                    [
                        'title' => 'Petition for a Spouse outside the U.S. – USCIS Petition only',
                        'subtitle' => 'I-130 petition for spouse outside the United States',
                        'starting_price' => '$549.99',
                        'processing_time' => '11-15 months',
                        'is_popular' => true,
                        'order_index' => 1,
                        'requirements' => [
                            "Legally married to U.S. citizen/resident",
                            "Spouse currently outside U.S.",
                            "Proof of bona fide marriage"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 549.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 789.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 999.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'Petition for a Child outside the U.S. – USCIS Petition only',
                        'subtitle' => 'I-130 petition for child outside the United States',
                        'starting_price' => '$549.99',
                        'processing_time' => '9-14 months',
                        'is_popular' => false,
                        'order_index' => 2,
                        'requirements' => [
                            "Valid parent-child relationship",
                            "Child currently outside U.S.",
                            "U.S. citizen or LPR petitioner"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 549.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 789.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 999.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'Petition for a Parent outside the U.S. – USCIS Petition only',
                        'subtitle' => 'I-130 petition for parent outside the United States',
                        'starting_price' => '$549.99',
                        'processing_time' => '10-15 months',
                        'is_popular' => false,
                        'order_index' => 3,
                        'requirements' => [
                            "Valid parent-child relationship",
                            "Citizen must be 21 or older",
                            "Proof of financial ability"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 549.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 789.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 999.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'Petition for a Sibling outside the U.S. – USCIS Petition only',
                        'subtitle' => 'I-130 petition for sibling outside the United States',
                        'starting_price' => '$549.99',
                        'processing_time' => '10-15 months',
                        'is_popular' => false,
                        'order_index' => 4,
                        'requirements' => [
                            "Sibling relationship to U.S. citizen",
                            "Citizen must be 21 or older",
                            "No criminal or immigration bars"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 549.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 789.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 999.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'K-1 Fiancé Visa – USCIS Petition only',
                        'subtitle' => 'I-129F petition for K-1 fiancé visa',
                        'starting_price' => '$549.99',
                        'processing_time' => '8-14 months',
                        'is_popular' => false,
                        'order_index' => 5,
                        'requirements' => [
                            "In-person meeting within last 2 years",
                            "Intent to marry within 90 days of entry",
                            "US citizen petitioner"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 549.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 789.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 999.99, 'order_index' => 3],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Renewals & Conditions',
                'subtitle' => 'Renew or update your existing immigration status',
                'pill_text' => 'Renewals & Conditions',
                'order_index' => 3,
                'services' => [
                    [
                        'title' => 'Petition to Remove Conditions on Conditional Residence – Joint Filing',
                        'subtitle' => 'I-751 petition to remove conditions (joint filing)',
                        'starting_price' => '$399.99',
                        'processing_time' => '18-24 months',
                        'application_type' => 'Applicant Only',
                        'is_popular' => false,
                        'order_index' => 1,
                        'requirements' => [
                            "Current conditional resident status",
                            "Filing within 90 days of expiration",
                            "Proof of ongoing bona fide marriage"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 399.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 499.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 699.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'Renew or Replace Permanent Resident Card (Green Card Renewal / I-90)',
                        'subtitle' => 'I-90 application to renew or replace green card',
                        'starting_price' => '$349.99',
                        'processing_time' => '8-12 months',
                        'application_type' => 'Applicant Only',
                        'is_popular' => true,
                        'order_index' => 2,
                        'requirements' => [
                            "Current or expired 10-year green card",
                            "Card lost, stolen, or damaged",
                            "No deportable offenses"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 349.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 449.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 599.99, 'order_index' => 3],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Other Immigration Services',
                'subtitle' => 'Additional immigration services including citizenship and DACA',
                'pill_text' => 'Other Immigration Services',
                'order_index' => 4,
                'services' => [
                    [
                        'title' => 'DACA Renewal (Deferred Action for Childhood Arrivals)',
                        'subtitle' => 'I-821D DACA renewal application',
                        'starting_price' => '$299.99',
                        'processing_time' => '3-6 months',
                        'application_type' => 'Applicant Only',
                        'is_popular' => false,
                        'order_index' => 1,
                        'requirements' => [
                            "Previously granted DACA",
                            "Did not depart US without advance parole",
                            "Continuous residence in US"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 299.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 399.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 539.99, 'order_index' => 3],
                        ]
                    ],
                    [
                        'title' => 'Application for U.S. Citizenship (Naturalization / N-400)',
                        'subtitle' => 'N-400 application for naturalization',
                        'starting_price' => '$349.99',
                        'processing_time' => '8-12 months',
                        'application_type' => 'Applicant Only',
                        'is_popular' => true,
                        'order_index' => 2,
                        'requirements' => [
                            "18 years or older",
                            "Permanent resident for 3-5 years",
                            "Good moral character"
                        ],
                        'packages' => [
                            ['name' => 'Basic Package', 'price' => 349.99, 'order_index' => 1],
                            ['name' => 'Advanced Package', 'price' => 449.99, 'order_index' => 2],
                            ['name' => 'Premium Package', 'price' => 649.99, 'order_index' => 3],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($categories as $catData) {
            $services = $catData['services'];
            unset($catData['services']);

            $category = ServiceCategory::create($catData);

            foreach ($services as $srvData) {
                $packages = $srvData['packages'];
                unset($srvData['packages']);

                $service = $category->services()->create($srvData);

                foreach ($packages as $pkgData) {
                    $service->packages()->create($pkgData);
                }
            }
        }
    }
}
