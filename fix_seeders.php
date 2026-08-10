<?php

$seeders = glob('database/seeders/Form*Seeder.php');

$replacements = [
    'FormG1145Seeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%G-1145%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%G-1145%\')->orWhere(\'subtitle\', \'like\', \'%G-1145%\')->first();'
    ],
    'FormI129FSeeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%I-129F%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%I-129F%\')->orWhere(\'subtitle\', \'like\', \'%I-129F%\')->first();'
    ],
    'FormI751Seeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%I-751%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%I-751%\')->orWhere(\'subtitle\', \'like\', \'%I-751%\')->first();'
    ],
    'FormI765Seeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%I-765%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%I-765%\')->orWhere(\'subtitle\', \'like\', \'%I-765%\')->first();'
    ],
    'FormI765WSSeeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%I-765WS%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%I-765WS%\')->orWhere(\'subtitle\', \'like\', \'%I-765WS%\')->first();'
    ],
    'FormI821DSeeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%I-821D%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%I-821D%\')->orWhere(\'subtitle\', \'like\', \'%I-821D%\')->first();'
    ],
    'FormI864Seeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%I-864%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%I-864%\')->orWhere(\'subtitle\', \'like\', \'%I-864%\')->first();'
    ],
    'FormI90Seeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%I-90%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%I-90%\')->orWhere(\'subtitle\', \'like\', \'%I-90%\')->first();'
    ],
    'FormN400Seeder.php' => [
        'search' => '$service = Service::where(\'title\', \'like\', \'%N-400%\')->first();',
        'replace' => '$service = Service::where(\'title\', \'like\', \'%N-400%\')->orWhere(\'subtitle\', \'like\', \'%N-400%\')->first();'
    ]
];

foreach ($seeders as $file) {
    $content = file_get_contents($file);
    $basename = basename($file);
    $changed = false;

    if (isset($replacements[$basename])) {
        if (strpos($content, $replacements[$basename]['search']) !== false) {
            $content = str_replace($replacements[$basename]['search'], $replacements[$basename]['replace'], $content);
            $changed = true;
        }
    }

    if ($changed) {
        file_put_contents($file, $content);
        echo "Updated $basename\n";
    }
}
