<?php

$path = isset($_SERVER['COMPOSER_AUTOLOADER']) ? $_SERVER['COMPOSER_AUTOLOADER'] :
                                                 dirname(__FILE__).'/../vendor/autoload.php';

if (!file_exists($path))
{
    die("Unable to find the composer autoloader, aborting!\n\n");
}

include $path;

// Make sure user has installed dev dependencies
$json = file_get_contents(__DIR__.'/../composer.lock');
$json = json_decode($json, TRUE);

if ($json['packages-dev'] === NULL)
{
    die("You have have not installed composer dependencies with --dev. Aborting PHPUnit tests!\n\n");
}