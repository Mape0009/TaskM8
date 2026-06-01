<?php
$en = include dirname(__DIR__) . '/lang/en/ui.php';
$taskPattern = '/task|shift|wizard|placeholder_task|volunteer|approve|reject|join_shift|not_allowed|back_to_tasks|back_to_shifts|create_shift|review_/i';
foreach (['uk', 'ru'] as $loc) {
    $data = include dirname(__DIR__) . "/lang/$loc/ui.php";
    echo "=== $loc ===\n";
    foreach ($en as $k => $v) {
        if (!is_array($v) && isset($data[$k]) && $data[$k] === $v && preg_match($taskPattern, $k)) {
            echo "$k\n";
        }
    }
}
