<?php

namespace App\Controllers;

use App\Core\View;

require_once 'helpers.php';

class TreeController
{
    private $database;

    public function __construct()
    {
        $this->database = database();
    }

    public function show(): void
    {
        if (!$_SESSION['loggedIn'] == true) {
            View::show('Authorization/index.html', ['notification' => 'Please log in first!']);
            return;
        }

        $list = $this->database->select('categories', '*');

        View::show('List/list.php', ['list' => $list]);
    }

    public function add($params): void
    {
        if (!$params['id'] == 0) {
            $element = $this->database->select('categories', '*', ['id[=]' => $params['id']])[0];
            View::show('List/add.php', ['element' => $element]);
        } else {
            View::show('List/add.php');
        }
    }

    public function create($params): void
    {
        if (!$params['id'] == 0) {
            $this->database->update('categories', [
                'has_child' => 1
            ], [
                'id[=]' => $params['id']
            ]);
        }

        $this->database->insert('categories', [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'parent_id' => $params['id'],
            'has_child' => 0
        ]);

        View::show('List/list.php');
    }

    public function edit($params): void
    {
        $element = $this->database->select('categories', '*', ['id[=]' => $params['id']])[0];
        View::show('List/edit.php', ['element' => $element]);
    }

    public function update($params): void
    {
        $this->database->update('categories',
            ['name' => $_POST['name'], 'description' => $_POST['description']],
            ['id[=]' => $params['id']]);

        View::show('List/list.php');
    }

    public function delete($params): void
    {
        $element = $this->database->select('categories', '*', ['id[=]' => $params['id']])[0];

        $this->database->delete("categories", [
            "AND" => [
                "id[=]" => $element['id']
            ]
        ]);

        if ($element['has_child'] == 1) {

            $id = $params['id'];
            while (true) {
                $children = $this->database->select('categories', '*', ['parent_id[=]' => $id]);

                foreach ($children as $child) {
                    $this->database->delete("categories", [
                        "AND" => [
                            "id[=]" => $child['id']
                        ]
                    ]);

                    if ($child['has_child'] == 1) {
                        $id = $child['id'];
                    }
                }

                if ($children[0]['has_child'] == 0) {
                    foreach ($children as $child) {
                        ;
                        $this->database->delete("categories", [
                            "AND" => [
                                "id[=]" => $child['id']
                            ]
                        ]);
                    }
                    break;
                }
            }
        }

        $this->database->delete("categories", [
            "AND" => [
                "parent_id[=]" => $element['id']
            ]
        ]);

        View::show('List/list.php');
    }
}