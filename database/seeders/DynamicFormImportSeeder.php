<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DynamicForm;
use App\Models\DynamicFormSection;
use App\Models\DynamicFormQuestion;
use App\Models\DynamicFormOption;

class DynamicFormImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonPath = base_path('../form_schemas.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("Could not find $jsonPath");
            return;
        }

        $json = file_get_contents($jsonPath);
        $schemas = json_decode($json, true);

        if (!$schemas) {
            $this->command->error("Invalid JSON data");
            return;
        }

        foreach ($schemas as $schema) {
            $this->command->info("Importing form: {$schema['slug']}");
            
            $form = DynamicForm::updateOrCreate(
                ['slug' => $schema['slug']],
                [
                    'name' => $schema['name'],
                    'description' => $schema['description']
                ]
            );

            // Delete old sections and questions to prevent duplicates on re-run
            $form->sections()->delete();

            foreach ($schema['sections'] as $sData) {
                $section = $form->sections()->create([
                    'title' => $sData['title'],
                    'description' => $sData['description'] ?? '',
                    'order' => $sData['order_index'],
                    'assignee_roles' => json_encode(['petitioner', 'applicant'])
                ]);

                foreach ($sData['questions'] as $qData) {
                    $question = $section->questions()->create([
                        'field_name' => $qData['field_name'],
                        'question_text' => $qData['question_text'],
                        'field_type' => $qData['field_type'],
                        'is_required' => $qData['is_required'] ?? false,
                        'help_text' => $qData['help_text'] ?? '',
                        'order' => $qData['order_index']
                    ]);
                }
            }
        }
    }
}
