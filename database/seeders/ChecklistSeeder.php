<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $json = file_get_contents(__DIR__ . '/checklists.json');
        $data = json_decode($json, true);

        if (!$data) {
            $this->command->error('Failed to parse checklists.json');
            return;
        }

        foreach ($data as $key => $checklist) {
            \App\Models\Checklist::updateOrCreate(
                ['key' => $key],
                [
                    'title' => $checklist['title'] ?? '',
                    'subtitle' => $checklist['subtitle'] ?? null,
                    'description' => $checklist['description'] ?? null,
                    'forms' => $checklist['forms'] ?? [],
                    'total_documents' => $checklist['totalDocuments'] ?? 0,
                    'sections' => $checklist['sections'] ?? []
                ]
            );
        }

        $this->command->info('Checklists seeded successfully!');
    }
}
