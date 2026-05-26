<?php

declare(strict_types=1);

/**
 * Build / sync lang/{locale}/ui.php from English master + translation maps.
 *
 * Usage: php scripts/build-ui-translations.php
 *        php scripts/build-ui-translations.php --generate-maps  (calls MyMemory; slow)
 */

$root = dirname(__DIR__);
require_once __DIR__ . '/translations/MyMemoryTranslator.php';
require_once __DIR__ . '/translations/GtxTranslator.php';

$enPath = $root . '/lang/en/ui.php';
$en = require $enPath;
if (!is_array($en)) {
    fwrite(STDERR, "Failed to load $enPath\n");
    exit(1);
}

$fullLocales = ['fi', 'it', 'uk', 'ru'];
$patchLocales = ['da', 'es'];
$allLocales = array_merge($fullLocales, $patchLocales);
$mapsDir = __DIR__ . '/translations/maps';

$langCodes = [
    'fi' => 'fi',
    'it' => 'it',
    'uk' => 'uk',
    'ru' => 'ru',
    'da' => 'da',
    'es' => 'es',
];

$generateMaps = in_array('--generate-maps', $argv, true);
$onlyLocales = array_values(array_filter($argv, fn ($a) => !str_starts_with($a, '--') && preg_match('/^[a-z]{2}$/', $a)));

if ($generateMaps) {
    if (!is_dir($mapsDir)) {
        mkdir($mapsDir, 0777, true);
    }
    $genFull = $onlyLocales ? array_intersect($fullLocales, $onlyLocales) : $fullLocales;
    $genPatch = $onlyLocales ? array_intersect($patchLocales, $onlyLocales) : $patchLocales;
    generateMaps($en, $genFull, $genPatch, $mapsDir, $langCodes, $root);
}

$maps = loadMaps($mapsDir, $allLocales);
$overrides = is_file(__DIR__ . '/translations/overrides.php')
    ? require __DIR__ . '/translations/overrides.php'
    : [];
foreach ($overrides as $locale => $pairs) {
    $maps[$locale] = array_merge($maps[$locale] ?? [], $pairs);
}

foreach ($fullLocales as $locale) {
    $existing = loadLocale($root, $locale);
    $merged = mergeTranslations($en, $maps[$locale] ?? [], $existing, preserveExisting: true);
    $merged = ensureLocalesBlock($merged, $locale);
    writeLocaleFile($root, $locale, $en, $merged);
    echo "Wrote lang/$locale/ui.php (" . count($merged) . " keys)\n";
}

foreach ($patchLocales as $locale) {
    $existing = loadLocale($root, $locale);
    $patch = $maps[$locale] ?? [];
    $missing = [];
    foreach ($en as $key => $english) {
        if (!array_key_exists($key, $existing)) {
            $missing[$key] = $english;
            continue;
        }
        if (is_array($english)) {
            continue;
        }
        if ((string) $existing[$key] === (string) $english) {
            $missing[$key] = $english;
        }
    }
    $toAdd = [];
    foreach ($missing as $key => $english) {
        if (isset($patch[$key]) && (is_array($patch[$key]) || (string) $patch[$key] !== (string) $english)) {
            $toAdd[$key] = $patch[$key];
        } elseif (is_array($english)) {
            $toAdd[$key] = $english;
        } else {
            $toAdd[$key] = MyMemoryTranslator::translate((string) $english, $langCodes[$locale]);
            usleep(150000);
        }
    }
    $merged = mergeTranslations($en, $toAdd, $existing, preserveExisting: true);
    $merged = ensureLocalesBlock($merged, $locale);
    writeLocaleFile($root, $locale, $en, $merged);
    echo "Patched lang/$locale/ui.php (" . count($merged) . " keys, added " . count($toAdd) . ")\n";
}

echo "\n=== Key counts ===\n";
$enCount = count($en);
foreach ($allLocales as $locale) {
    $loc = loadLocale($root, $locale);
    $status = count($loc) === $enCount ? 'OK' : 'MISMATCH';
    echo "$locale: " . count($loc) . " ($status)\n";
}

echo "\n=== Keys still matching English (non-en locales) ===\n";
foreach ($allLocales as $locale) {
    $loc = loadLocale($root, $locale);
    $stillEnglish = [];
    foreach ($en as $key => $english) {
        if (!isset($loc[$key])) {
            continue;
        }
        if (is_array($english)) {
            if ($loc[$key] === $english) {
                $stillEnglish[] = $key;
            }
            continue;
        }
        if ((string) $loc[$key] === (string) $english) {
            $stillEnglish[] = $key;
        }
    }
    echo "$locale: " . count($stillEnglish) . " keys\n";
    foreach ($stillEnglish as $key) {
        echo "  - $key\n";
    }
}

function loadLocale(string $root, string $locale): array
{
    $path = "$root/lang/$locale/ui.php";
    if (!is_file($path)) {
        return [];
    }
    $data = require $path;
    return is_array($data) ? $data : [];
}

function loadMaps(string $mapsDir, array $locales): array
{
    $maps = [];
    foreach ($locales as $locale) {
        $file = "$mapsDir/$locale.php";
        if (is_file($file)) {
            $data = require $file;
            if (is_array($data)) {
                $maps[$locale] = $data;
            }
        }
    }
    return $maps;
}

function mergeTranslations(array $en, array $map, array $existing, bool $preserveExisting): array
{
    $out = [];
    foreach ($en as $key => $english) {
        $englishScalar = is_array($english) ? null : (string) $english;

        if ($preserveExisting && array_key_exists($key, $existing)) {
            $cur = $existing[$key];
            if (is_array($cur)) {
                $out[$key] = $cur;
                continue;
            }
            $curStr = (string) $cur;
            if ($curStr !== '' && ($englishScalar === null || $curStr !== $englishScalar)) {
                $out[$key] = $cur;
                continue;
            }
        }

        if (array_key_exists($key, $map)) {
            $mapped = $map[$key];
            if (is_array($mapped)) {
                $out[$key] = $mapped;
                continue;
            }
            $mappedStr = (string) $mapped;
            if ($mappedStr !== '' && ($englishScalar === null || $mappedStr !== $englishScalar)) {
                $out[$key] = $mapped;
                continue;
            }
        }

        $out[$key] = $english;
    }
    return $out;
}

function ensureLocalesBlock(array $merged, string $locale): array
{
    $labels = [
        'da' => 'Danish',
        'en' => 'English',
        'es' => 'Spanish',
        'fi' => 'Finnish',
        'it' => 'Italian',
        'uk' => 'Ukrainian',
        'ru' => 'Russian',
    ];
    $native = [
        'da' => 'Dansk',
        'en' => 'English',
        'es' => 'Español',
        'fi' => 'Suomi',
        'it' => 'Italiano',
        'uk' => 'Українська',
        'ru' => 'Русский',
    ];
    $locales = [];
    foreach (['da', 'en', 'es', 'fi', 'it', 'uk', 'ru'] as $code) {
        $locales[$code] = $native[$code] ?? $labels[$code];
    }
    $merged['locales'] = $locales;
    return $merged;
}

function writeLocaleFile(string $root, string $locale, array $en, array $merged): void
{
    $path = "$root/lang/$locale/ui.php";
    $lines = ["<?php", '', 'return ['];
    foreach (array_keys($en) as $key) {
        if (!array_key_exists($key, $merged)) {
            continue;
        }
        $lines[] = '    ' . exportKey($key) . ' => ' . exportValue($merged[$key]) . ',';
    }
    $lines[] = '];';
    $lines[] = '';
    file_put_contents($path, implode("\n", $lines));
}

function exportKey(string $key): string
{
    return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], $key) . "'";
}

function exportValue(mixed $value): string
{
    if (!is_array($value)) {
        return var_export((string) $value, true);
    }
    $parts = ['['];
    foreach ($value as $k => $v) {
        $parts[] = '        ' . exportKey((string) $k) . ' => ' . var_export((string) $v, true) . ',';
    }
    $parts[] = '    ]';
    return implode("\n", $parts);
}

function generateMaps(
    array $en,
    array $fullLocales,
    array $patchLocales,
    string $mapsDir,
    array $langCodes,
    string $root,
): void {
    foreach (array_merge($fullLocales, $patchLocales) as $locale) {
        $existing = loadLocale($root, $locale);
        $keys = in_array($locale, $fullLocales, true) ? $en : array_diff_key($en, $existing);
        echo "Generating map for $locale (" . count($keys) . " keys)...\n";
        $map = [];
        $n = 0;
        foreach ($keys as $key => $value) {
            if (is_array($value)) {
                $map[$key] = $value;
                continue;
            }
            $map[$key] = GtxTranslator::translate((string) $value, $langCodes[$locale]);
            $n++;
            if ($n % 25 === 0) {
                echo "  $n / " . count($keys) . "\n";
            }
            usleep(80000);
        }
        $out = "$mapsDir/$locale.php";
        file_put_contents($out, exportMapFile($map));
        echo "Wrote $out\n";
    }
}

function exportMapFile(array $map): string
{
    $lines = ["<?php", '', 'declare(strict_types=1);', '', 'return ['];
    foreach ($map as $key => $value) {
        $lines[] = '    ' . exportKey((string) $key) . ' => ' . exportValue($value) . ',';
    }
    $lines[] = '];';
    $lines[] = '';
    return implode("\n", $lines);
}
