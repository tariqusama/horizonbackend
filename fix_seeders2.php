<?php

$files = glob(__DIR__ . '/database/seeders/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $basename = basename($file);
    $changed = false;

    // Pattern for DynamicForm::create or updateOrCreate
    if (preg_match("/'service_id'\s*=>\s*(\\\$[a-zA-Z0-9_]+|[0-9]+)/", $content, $matches)) {
        $serviceIdVar = $matches[1];
        
        // Remove 'service_id' => ...
        $content = preg_replace("/'service_id'\s*=>\s*(\\\$[a-zA-Z0-9_]+|[0-9]+)\s*,?\s*/", '', $content);
        
        // Inject sync
        $content = preg_replace(
            "/(updateOrCreate\([^;]+;\s*)/s",
            "$1\n    \$form->services()->syncWithoutDetaching([$serviceIdVar]);\n",
            $content
        );

        $content = preg_replace(
            "/(create\([^;]+;\s*)/s",
            "$1\n    \$form->services()->syncWithoutDetaching([$serviceIdVar]);\n",
            $content
        );
        
        file_put_contents($file, $content);
        echo "Fixed service_id in $basename\n";
    }
}
echo "Done.\n";
