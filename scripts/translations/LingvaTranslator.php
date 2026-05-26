<?php

declare(strict_types=1);

final class LingvaTranslator
{
    private const INSTANCES = [
        'https://lingva.ml',
        'https://translate.plausibility.cloud',
    ];

    public static function translate(string $text, string $targetLang): string
    {
        if ($text === '') {
            return $text;
        }

        [$protected, $map] = self::protect($text);
        $encoded = rawurlencode($protected);

        foreach (self::INSTANCES as $base) {
            $url = rtrim($base, '/') . '/api/v1/en/' . rawurlencode($targetLang) . '/' . $encoded;
            $ctx = stream_context_create(['http' => ['timeout' => 30]]);
            $json = @file_get_contents($url, false, $ctx);
            if ($json === false) {
                continue;
            }
            $data = json_decode($json, true);
            $translated = $data['translation'] ?? null;
            if (is_string($translated) && $translated !== '') {
                return self::restore($translated, $map);
            }
        }

        return $text;
    }

    private static function protect(string $text): array
    {
        $map = [];
        $i = 0;
        $text = preg_replace_callback('/:\w+/', function ($m) use (&$map, &$i) {
            $key = '__PH' . $i . '__';
            $map[$key] = $m[0];
            $i++;
            return $key;
        }, $text);
        foreach (['TaskM8' => '__TASKM8__', 'Mercantec' => '__MERC__'] as $word => $token) {
            if (str_contains($text, $word)) {
                $map[$token] = $word;
                $text = str_replace($word, $token, $text);
            }
        }
        return [$text, $map];
    }

    private static function restore(string $text, array $map): string
    {
        foreach ($map as $k => $v) {
            $text = str_replace($k, $v, $text);
        }
        return $text;
    }
}
