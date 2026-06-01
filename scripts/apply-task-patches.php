<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$en = require "$root/lang/en/ui.php";

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

function ensureLocalesBlock(array $merged, string $locale): array
{
    $native = [
        'da' => 'Dansk', 'en' => 'English', 'es' => 'Español',
        'fi' => 'Suomi', 'it' => 'Italiano', 'uk' => 'Українська', 'ru' => 'Русский',
    ];
    $locales = [];
    foreach (['da', 'en', 'es', 'fi', 'it', 'uk', 'ru'] as $code) {
        $locales[$code] = $native[$code];
    }
    $merged['locales'] = $locales;
    return $merged;
}

foreach (['uk' => __DIR__ . '/patches/uk-tasks.php', 'ru' => __DIR__ . '/patches/ru-tasks.php'] as $locale => $patchFile) {
    $data = require "$root/lang/$locale/ui.php";
    $patch = require $patchFile;
    $data = array_merge($data, $patch);
    $data = ensureLocalesBlock($data, $locale);
    writeLocaleFile($root, $locale, $en, $data);
    echo "Patched $locale with " . count($patch) . " keys\n";
}
