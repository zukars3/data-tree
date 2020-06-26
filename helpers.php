<?php

use App\Core\Config\ConfigManager;
use App\Core\Config\FileConfig;
use App\Core\Database;
use Medoo\Medoo;

function database(): Medoo
{
    return Database::getInstance()->connection();
}

function config(): ConfigManager
{
    $config = include(__DIR__ . '/config/config.php');
    return new FileConfig($config);
}