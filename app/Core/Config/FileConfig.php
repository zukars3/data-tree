<?php

namespace App\Core\Config;

class FileConfig implements ConfigManager
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(string $name): string
    {
        return $this->config[$name];
    }
}