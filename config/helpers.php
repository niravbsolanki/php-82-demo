<?php
if (!function_exists('dd')) {
    function dd(...$vars): void
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        exit; // Kill the script just like Laravel dd()
    }
}

if (!function_exists('dump')) {
    function dump(...$vars): void
    {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
    }
}

if (!function_exists('dlog')) {
    function dlog($var, string $label = 'DEBUG'): void
    {
        error_log("[$label] " . print_r($var, true));
    }
}
