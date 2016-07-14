<?php

/**
 * @package     omeka
 * @subpackage  moas-niceurls
 * @copyright   2016 University of Nottingham
 * @license     MIT
 * @author      James Hodgson <james.hodgson@nottingham.ac.uk>
 */

class MOASNiceUrls_Helpers_String
{
    /**
     * @param $string
     * @param null $count
     * @param int $start
     * @return array
     */
    public static function words($string, $count = null, $start = 0)
    {
        return array_slice(str_word_count($string, 1), $start, $count);
    }

    /**
     * Removes all punctuation but spaces from the supplied string
     *
     * @param string $string the string to strip punctuation from
     * @param bool $ignoreSpaces Should spaces be stripped along with punctuation
     * @return string
     */
    public static function stripPunctuation($string, $ignoreSpaces = true)
    {
        $patterns = static::getPatterns();
        if ($ignoreSpaces) {
            return preg_replace($patterns['punctuation'], '', $string);
        } else {
            return preg_replace($patterns['punctuationWithSpaces'], '', $string);
        }
    }

    /**
     * Truncate a string to the nearest words that fits within the character length.
     * Punctuation/Spaces are not counted towards the character length
     *
     * @param string $string String to truncate
     * @param int $charLength Max length of the string
     * @return string string truncated string
     */
    public static function truncateWords($string, $charLength)
    {
        $parts = static::words($string);
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
            $length += strlen($parts[$last_part]);
            if ($length > $charLength) { break; }
        }

        return implode(array_slice($parts, 0, $last_part));
    }

    /**
     * Replace the last instance of a given string.
     *
     * @param string $needle the string to replace
     * @param string $with what to replace $needle with
     * @param string $haystack the string to replace it in
     * @return string
     */
    public static function replaceLast($needle, $with, $haystack)
    {
        $pos = strrpos($haystack, $needle);
        if ($pos !== false)
        {
            $haystack = substr_replace($haystack, $with, $pos, strlen($needle));
        }
        return $haystack;
    }

    /**
     * Check the string for punctuation
     *
     * @param string $string the string to check
     * @param bool $ignoreSpaces Should spaces be ignored
     * @return int
     */
    public static function hasPunctuation($string, $ignoreSpaces = true)
    {
        $patterns = static::getPatterns();
        if ($ignoreSpaces) {
            return preg_match($patterns['punctuation'], $string);
        } else {
            return preg_match($patterns['punctuationWithSpaces'], $string);
        }
    }


    /**
     * Get an array of regex patterns to be used for various tasks
     *
     * @return array
     */
    private static function getPatterns()
    {
        return array(
            'punctuation' => '/[^a-z\d ]+/i',
            'punctuationWithSpaces' => '/[^a-z\d]+/i'
        );
    }
}