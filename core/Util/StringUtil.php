<?php

namespace Core\Util;

class StringUtil
{
    public static function replaceIntoQuotes(
        string $search,
        string $to,
        string $content
    ): string {
        $pattern = "/\"[^\"]*\"|'[^']*'/";

        preg_match_all($pattern, $content, $matches);
        $replaced = $content;

        foreach ($matches[0] as $match) {
            $matchReplaced = str_replace($search, $to, $match);
            $replaced = self::replaceFirst($match, $matchReplaced, $replaced);
        }

        return $replaced;
    }

    public static function replaceFirst($from, $to, $content): string
    {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $content, 1);
    }

    public static function getPositionFirstLineWithContent(string $content): int
    {
        $noBlankLines = self::filterNoBlankLines($content);
        return array_key_first($noBlankLines);
    }

    public static function filterNoBlankLines(string $content): array
    {
        $lines = explode("\n", $content);

        return array_filter($lines, static function ($line) {
            return trim($line) !== '' && trim($line) !== "\n";
        });
    }
}
