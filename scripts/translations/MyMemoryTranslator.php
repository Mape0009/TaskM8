<?php

declare(strict_types=1);

final class MyMemoryTranslator
{
    private const API = 'https://api.mymemory.translated.net/get';

    public static function translate(string $text, string $targetLang): string
    {
        if ($text === '' || preg_match('/^[\d\s\W]+$/u', $text)) {
            return $text;
        }

        [$protected, $map] = self::protect($text);
        $url = self::API . '?' . http_build_query([
            'q' => $protected,
            'langpair' => 'en|' . $targetLang,
            'de' => 'taskm8@local.dev',
        ]);

        for ($attempt = 0; $attempt < 4; $attempt++) {
            $ctx = stream_context_create(['http' => ['timeout' => 45]]);
            $json = @file_get_contents($url, false, $ctx);
            if ($json === false) {
                usleep(500000 * ($attempt + 1));
                continue;
            }
            $data = json_decode($json, true);
            $translated = $data['responseData']['translatedText'] ?? null;
            if (!is_string($translated) || $translated === '') {
                usleep(500000 * ($attempt + 1));
                continue;
            }
            $restored = self::restore($translated, $map);
            if ($restored !== $text) {
                return $restored;
            }
            usleep(500000 * ($attempt + 1));
        }

        require_once __DIR__ . '/GtxTranslator.php';
        $gtx = GtxTranslator::translate($text, $targetLang);
        if ($gtx !== $text) {
            return $gtx;
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
        if (str_contains($text, 'TaskM8')) {
            $map['__TASKM8__'] = 'TaskM8';
            $text = str_replace('TaskM8', '__TASKM8__', $text);
        }
        if (str_contains($text, 'Mercantec')) {
            $map['__MERC__'] = 'Mercantec';
            $text = str_replace('Mercantec', '__MERC__', $text);
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
