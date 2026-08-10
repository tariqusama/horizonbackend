<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SignupGoal;

class SignupPathwaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pathways = [
            "Replace or fix a Green Card" => [
                'image_url' => "url('https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1200&q=80')",
                'order_index' => 1,
                'questions' => [
                    [
                        'question_text' => "Do you currently live in the United States?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "What is your current immigration status?",
                        'options' => ["I have permanent resident status", "I have non-permanent resident status"],
                        'disqualifying_options' => ["I have non-permanent resident status"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "What is the current status of your Green Card?",
                        'options' => ["Lost, Stolen, Damage or Destroyed Green Card", "Card Expired or Expiring Soon", "Card Issued but Never Received", "Incorrect Information on Card (USCIS Error)", "Biographic Information Changed (Name)", "Biographic Information Changed (Gender)", "Turning 14 Years Old", "None of the Above"],
                        'skip_to_end_options' => ["Lost, Stolen, Damage or Destroyed Green Card", "Card Issued but Never Received", "Incorrect Information on Card (USCIS Error)", "Biographic Information Changed (Name)", "Biographic Information Changed (Gender)", "Turning 14 Years Old"],
                        'disqualifying_options' => ["None of the Above"]
                    ]
                ]
            ],
            "Bring a fiancé(e) or spouse/relative to the U.S." => [
                'image_url' => "url('https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=1200&q=80')",
                'order_index' => 2,
                'questions' => [
                    [
                        'question_text' => "Who do you want to bring to the United States?",
                        'options' => ["Fiancé(e)", "Spouse", "Child/Step Child", "Parent", "Sibling"],
                        'disqualifying_options' => null,
                        'skip_to_end_options' => null
                    ]
                ]
            ],
            "Adjust status to permanent resident / get a Green Card while in US" => [
                'image_url' => "url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80')",
                'order_index' => 3,
                'questions' => [
                    [
                        'question_text' => "Are you currently in the United States?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Did you enter the United States through \"inspection and admission\" or \"inspection and parole\"?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "What criteria do you meet to qualify for a Green Card?",
                        'options' => ["Family", "Employment", "Asylum/Special US government provisions", "None of the Above"],
                        'disqualifying_options' => ["Employment", "Asylum/Special US government provisions", "None of the Above"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "What's your family relationship with the petitioner?",
                        'options' => ["Spouse", "Child", "Parent", "None of the above"],
                        'disqualifying_options' => ["None of the above"],
                        'skip_to_end_options' => null
                    ]
                ]
            ],
            "Remove conditions on residence (marriage-based conditional LPR)" => [
                'image_url' => "url('https://images.unsplash.com/photo-1494496545165-4f0be2d4bd51?auto=format&fit=crop&w=1200&q=80')",
                'order_index' => 4,
                'questions' => [
                    [
                        'question_text' => "Do you currently hold a Green Card?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "What is the basis for your conditional green card?",
                        'options' => ["A marriage to a U.S. citizen or legal permanent resident (LPR)", "My parents' marriage to a U.S. citizen or legal permanent resident (LPR)", "Employment in the U.S."],
                        'disqualifying_options' => ["Employment in the U.S."],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Are you currently residing in the United States?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "How do you plan to file?",
                        'options' => ["File jointly with my spouse", "Request a waiver to file alone"],
                        'disqualifying_options' => ["Request a waiver to file alone"],
                        'skip_to_end_options' => null
                    ]
                ]
            ],
            "DACA (Deferred Action) — Renewal" => [
                'image_url' => "url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80')",
                'order_index' => 5,
                'questions' => [
                    [
                        'question_text' => "What is the current status of your DACA?",
                        'options' => ["My DACA has not yet expired", "My DACA expired less than one year ago", "My DACA expired more than one year ago", "My DACA was terminated by USCIS"],
                        'disqualifying_options' => ["My DACA expired more than one year ago", "My DACA was terminated by USCIS"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Have you maintained continuous residence in the U.S. since your last DACA was Approved?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Have you been convicted of any of the following since your last DACA approval?",
                        'options' => ["A felony", "A significant misdemeanor", "Three or more other misdemeanors (that occurred on different dates and did not arise from the same incident)", "No, I have not been convicted of any of the above"],
                        'disqualifying_options' => ["A felony", "A significant misdemeanor", "Three or more other misdemeanors (that occurred on different dates and did not arise from the same incident)"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Do you pose a threat to national security or public safety?",
                        'options' => ["No", "Yes"],
                        'disqualifying_options' => ["Yes"],
                        'skip_to_end_options' => null
                    ]
                ]
            ],
            "Apply for U.S. Citizenship (Naturalization)" => [
                'image_url' => "url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80')",
                'order_index' => 6,
                'questions' => [
                    [
                        'question_text' => "Were either or both of your parents U.S. citizens at the time of your birth?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["Yes"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Are you 18 years old or older?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Are you a member of the United States armed forces?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["Yes"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Are you a lawful permanent resident of the United States?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "How long have you been a lawful permanent resident of the United States?",
                        'options' => ["At least 4 years and 9 months", "At least 2 years and 9 months, married to a U.S. citizen during that time", "None of the above"],
                        'disqualifying_options' => ["None of the above"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Are you currently in the United States and have you maintained continuous residence here?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ],
                    [
                        'question_text' => "Have you been physically present in the United States for at least 30 months during the past 5 years, OR at least 18 months in the last 3 years if you are married to a US citizen?",
                        'options' => ["Yes", "No"],
                        'disqualifying_options' => ["No"],
                        'skip_to_end_options' => null
                    ]
                ]
            ]
        ];

        foreach ($pathways as $title => $data) {
            $goal = SignupGoal::create([
                'title' => $title,
                'image_url' => $data['image_url'],
                'order_index' => $data['order_index']
            ]);

            foreach ($data['questions'] as $qIndex => $qData) {
                $goal->questions()->create([
                    'question_text' => $qData['question_text'],
                    'options' => $qData['options'],
                    'disqualifying_options' => $qData['disqualifying_options'],
                    'skip_to_end_options' => $qData['skip_to_end_options'],
                    'order_index' => $qIndex + 1
                ]);
            }
        }
    }
}
