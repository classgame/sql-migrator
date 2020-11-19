<?php

if (!function_exists('env')) {
    function env(string $str)
    {
        $rootDir = root_path();
        $dotenv = \Dotenv\Dotenv::createImmutable($rootDir);
        $dotenv->load();

        return $_ENV[$str];
    }
}

if (!function_exists('root_path')) {
    function root_path(string $path = ''): string
    {
        $rootDir = __DIR__ . '/sql-migrator/' . $path;
        return realpath($rootDir);
    }
}
