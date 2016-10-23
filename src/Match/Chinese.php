<?php

namespace FacebookAnonymousPublisher\Wordfilter\Match;

use Overtrue\Pinyin\Pinyin;

class Chinese implements Match
{
    /**
     * Determines the given text contain any of words.
     *
     * @param string $text
     * @param array $words
     *
     * @return bool
     */
    public static function match($text, array $words)
    {
        $pinyin = new Pinyin();

        $text = $pinyin->sentence($text);

        foreach ($words as $word) {
            if (false !== mb_strpos($text, $pinyin->sentence($word))) {
                return true;
            }
        }

        return false;
    }
}
