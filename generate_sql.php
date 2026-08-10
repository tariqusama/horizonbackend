<?php
$data = json_decode(file_get_contents(__DIR__ . '/database/seeders/checklists.json'), true);
$sql = "";
foreach($data as $key => $c) {
    $json = json_encode($c['sections'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $json = str_replace("'", "''", $json); // Escape single quotes for SQL
    $sql .= "UPDATE checklists SET sections = '$json' WHERE `key` = '$key';\n";
}
file_put_contents(__DIR__ . '/update_checklists.sql', $sql);
echo "SQL file generated.";
