<?php

namespace App\Core;

class View
{
    public static function show(string $path, array $variables = [])
    {
        extract($variables);

        include 'app/View/' . $path;
    }
}