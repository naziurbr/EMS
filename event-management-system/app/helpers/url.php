<?php

if (!function_exists('base_url')) {
    function base_url($path = '') {
        $baseUrl = rtrim('http://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        return base_url('public/' . ltrim($path, '/'));
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return url('assets/' . ltrim($path, '/'));
    }
}
