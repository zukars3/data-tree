<?php

namespace App\Core\Config;

interface ConfigManager
{
    public function get(string $name): string;
}