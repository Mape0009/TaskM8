<?php
$en = include dirname(__DIR__) . '/lang/en/ui.php';
foreach (['uk', 'ru', 'it', 'fi'] as $loc) {
    $data = include dirname(__DIR__) . "/lang/$loc/ui.php";
    $keys = [];
    foreach ($en as $k => $v) {
        if (!is_array($v) && isset($data[$k]) && $data[$k] === $v) {
            $keys[] = $k;
        }
    }
    echo "$loc: " . count($keys) . "\n";
    if (count($keys) <= 30) {
        foreach ($keys as $k) {
            echo "  $k\n";
        }
    }
}
