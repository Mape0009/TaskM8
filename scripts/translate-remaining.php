<?php

declare(strict_types=1);

require_once __DIR__ . '/translations/MyMemoryTranslator.php';

$root = dirname(__DIR__);
$en = require "$root/lang/en/ui.php";
$locales = $argv[1] ?? 'ru,uk';
$targets = array_map('trim', explode(',', $locales));

function writeLocaleFile(string $root, string $locale, array $en, array $merged): void
{
    $path = "$root/lang/$locale/ui.php";
    $lines = ["<?php", '', 'return ['];
    foreach (array_keys($en) as $key) {
        if (!array_key_exists($key, $merged)) {
            continue;
        }
        $value = $merged[$key];
        if (is_array($value)) {
            $lines[] = "    '$key' => [";
            foreach ($value as $k => $v) {
                $lines[] = '        ' . var_export($k, true) . ' => ' . var_export((string) $v, true) . ',';
            }
            $lines[] = '    ],';
        } else {
            $lines[] = '    ' . var_export($key, true) . ' => ' . var_export((string) $value, true) . ',';
        }
    }
    $lines[] = '];';
    $lines[] = '';
    file_put_contents($path, implode("\n", $lines));
}

$langCodes = ['uk' => 'uk', 'ru' => 'ru'];

foreach ($targets as $locale) {
    if (!isset($langCodes[$locale])) {
        continue;
    }
    $path = "$root/lang/$locale/ui.php";
    $data = require $path;
    $updated = 0;
    foreach ($en as $key => $english) {
        if (is_array($english)) {
            continue;
        }
        if (!isset($data[$key]) || (string) $data[$key] !== (string) $english) {
            continue;
        }
        $translated = MyMemoryTranslator::translate((string) $english, $langCodes[$locale]);
        if ($translated !== (string) $english) {
            $data[$key] = $translated;
            $updated++;
        }
        usleep(200000);
    }
    writeLocaleFile($root, $locale, $en, $data);
    echo "Updated $locale: $updated keys\n";
}
