<?php

$files = glob(__DIR__ . '/database/seeders/Form*.php');
$files[] = __DIR__ . '/database/seeders/DynamicFormsSeeder.php';

foreach ($files as $file) {
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);
    $basename = basename($file);

    // 1. Remove all instances of the accidentally injected line
    $content = preg_replace("/\s*\\\$form->services\(\)->syncWithoutDetaching\(\[.*?\]\);/", "", $content);

    // 2. We also need to ensure 'service_id' is removed from updateOrCreate arrays
    // My previous script removed 'service_id' => $something, but maybe we should ensure it's clean.
    // It's probably already removed, but just in case:
    $content = preg_replace("/'service_id'\s*=>\s*\\\$[a-zA-Z0-9_]+,\s*/", '', $content);
    $content = preg_replace("/'service_id'\s*=>\s*[0-9]+,\s*/", '', $content);

    // 3. Now, inject the sync line ONLY after DynamicForm::updateOrCreate
    // We match: $form = DynamicForm::updateOrCreate(...);
    // Note: updateOrCreate takes two arrays. It ends with );
    
    // Using a callback to properly append after the statement
    $content = preg_replace_callback(
        "/(\\\$form\s*=\s*DynamicForm::updateOrCreate\s*\([^;]+;\s*)/s",
        function ($matches) {
            // Check if it's in a loop with $serviceId or just $serviceId
            return $matches[1] . "\n    if (isset(\$serviceId)) { \$form->services()->syncWithoutDetaching([\$serviceId]); }\n";
        },
        $content
    );

    file_put_contents($file, $content);
    echo "Cleaned up and fixed $basename\n";
}
echo "Done.\n";
