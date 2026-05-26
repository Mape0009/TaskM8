<?php

declare(strict_types=1);

final class GtxTranslator
{
    public static function translate(string $text, string $targetLang): string
    {
        if ($text === '') {
            return $text;
        }

        [$protected, $map] = self::protect($text);
        $url = 'https://translate.googleapis.com/translate_a/single?' . http_build_query([
            'client' => 'gtx',
            'sl' => 'en',
            'tl' => $targetLang,
            'dt' => 't',
            'q' => $protected,
        ]);

        $ctx = stream_context_create(['http' => ['timeout' => 30, 'header' => "User-Agent: TaskM8-build/1.0\r\n"]]);
        $json = @file_get_contents($url, false, $ctx);
        if ($json === false) {
            return $text;
        }
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data[0][0][0])) {
            return $text;
        }
        $translated = (string) $data[0][0][0];
        return self::restore($translated, $map);
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
