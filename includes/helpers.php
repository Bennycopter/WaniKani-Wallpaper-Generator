<?php defined("INDEX") or die;

// This file adds basic, non-specific functionality to PHP.
// They are features that I wish PHP had.

function clamp($var, $min, $max) {
    return max(min($var,$max),$min);
}

/**
 * Returns a number between **A** and **B**, based on **weight**.
 *
 * Returns **A** when **weight** = 0,
 * and **B** when **weight** = 1.
 *
 * @param float $a Value A
 * @param float $b Value B
 * @param float $weight Number from 0 to 1
 * @return float Interpolated value
 */
function interpolate($a, $b, $weight) {
    return $a + ($b-$a) * $weight;
}

function imagecolorallocate_from_hex($image, $hex) {
    // Keep only alphanumeric characters
    $hex = preg_replace("/[^a-zA-Z0-9]+/", "", $hex);

    // Get decimal values from hex
    $values = array_map("hexdec", str_split($hex, 2));

    // Allocate Color
    return imagecolorallocate($image, $values[0], $values[1], $values[2]);
}

function file_put_prepended($string, $filename) {
    $context = stream_context_create();
    $orig_file = fopen($filename, 'r', 1, $context);

    $temp_filename = "temp.".rand().".txt";
    file_put_contents($temp_filename, $string);
    file_put_contents($temp_filename, $orig_file, FILE_APPEND);

    fclose($orig_file);
    unlink($filename);
    rename($temp_filename, $filename);
}

function file_put_json($file, $array) {
    file_put_contents($file, json_encode($array, JSON_UNESCAPED_UNICODE));
}
function file_get_json($file) {
    return json_decode(file_get_contents($file), true);
}



if (PHP_VERSION_ID < 70400) {
    // mb_str_split is only available in PHP 7 >= 7.4.0
    // see https://www.php.net/manual/en/function.mb-str-split.php
    function mb_str_split(string $string, int $split_length=1, string $encoding=null) : array {
        $encoding = $encoding ?? mb_internal_encoding();
        $length = mb_strlen($string, $encoding);
        $pieces = [];
        for ($i = 0; $i < $length; $i += $split_length) {
            $pieces[] = mb_substr($string, $i, $split_length, $encoding);
        }
        return $pieces;
    }
}