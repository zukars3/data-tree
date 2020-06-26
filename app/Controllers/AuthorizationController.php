<?php

namespace App\Controllers;

use App\Core\View;

require_once 'helpers.php';

class AuthorizationController
{
    private $database;

    public function __construct()
    {
        $this->database = database();
    }

    public function index(): void
    {
        if ($_SESSION['loggedIn'] == true) {
            View::show('List/list.php');
            exit();
        }
        View::show('Authorization/index.html');
    }

    public function logout(): void
    {
        $_SESSION['loggedIn'] = false;
        View::show('Authorization/index.html');
    }

    public function login(array $params): void
    {
        if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] !== "" && $_POST['password'] !== "") {
            if ($this->database->has('user_login_data', [
                'AND' => [
                    'OR' => [
                        'nickname' => $_POST['username'],
                        'email' => $_POST['username']
                    ],
                    'password' => $_POST['password']
                ]
            ])) {
                session_start();
                $_SESSION["loggedIn"] = true;
                header('Location: /list');
            } else {
                View::show('Authorization/index.html', ['notification' => 'Wrong username or password!']);
            }
        } else {
            View::show('Authorization/index.html', ['notification' => 'Please enter both username and password!']);
        }
    }

    public function registerShow(): void
    {
        View::show('Authorization/register.html');
    }

    public function register(): void
    {
        $completion = [
            'nickname' => false,
            'email' => false,
            'password' => false,
        ];

        if (isset($_POST['nickname']) && $_POST['nickname'] !== "" && strlen($_POST['nickname']) > 3) {
            $completion['nickname'] = true;
        }
        if (isset($_POST['email']) && $_POST['email'] !== "") {
            $completion['email'] = true;
        }
        if (isset($_POST['password']) && $_POST['password'] !== "" && strlen($_POST['password']) > 5) {
            $completion['password'] = true;
        }

        if ($this->database->has('user_login_data', [
            'nickname' => $_POST['nickname']
        ])) {
            $completion['nickname'] = false;
        } else {
            $completion['nickname'] = true;
        }

        if ($this->database->has('user_login_data', [
            'email' => $_POST['email']
        ])) {
            $completion['email'] = false;
        } else {
            $completion['email'] = true;
        }

        if (in_array(false, $completion, true)) {
            $fail = array_search(false, $completion);
            View::show('Authorization/register.html', ['error' => "This $fail is taken!"]);
            return;
        }

        $this->database->insert('user_login_data', [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'nickname' => $_POST['nickname']
        ]);

        View::show('Authorization/index.html', ['notification' => 'Thank you for registering! Now you can log in.']);
    }
}