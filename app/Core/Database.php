<?php

namespace App\Core;

use Medoo\Medoo;

class Database
{
    private $connection;
    public static $instance = null;

    public function __construct()
    {
        if (self::$instance === null) {
            self::$instance = $this;
        }

        $this->connection = new Medoo([
            'database_type' => 'mysql',
            'database_name' => config()->get('database_name'),
            'server' => config()->get('server'),
            'username' => config()->get('username'),
            'password' => config()->get('password')
        ]);
    }

    public static function getInstance(): self
    {
        return self::$instance ?? new Database();
    }

    public function connection(): Medoo
    {
        return $this->connection;
    }
}