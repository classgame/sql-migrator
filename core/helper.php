<?php

if (!function_exists('env')) {
    function env(string $str)
    {
        $rootDir = __DIR__ . '/../.';
        $dotenv = \Dotenv\Dotenv::createImmutable($rootDir);
        $dotenv->load();

        return $_ENV[$str];
    }
}
