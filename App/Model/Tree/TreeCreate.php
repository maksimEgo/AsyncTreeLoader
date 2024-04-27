<?php

namespace App\Model\Tree;

use Swoole\Coroutine;
use Swoole\Coroutine\PostgreSQL;

class TreeCreate
{
    public function createBinaryTree($depth, $value = 1): ?array
    {
        if ($depth == 0) return null;
        return [
            'value' => $value,
            'left' => $this->createBinaryTree($depth - 1, $value * 2),
            'right' => $this->createBinaryTree($depth - 1, $value * 2 + 1),
        ];
    }

    public function serializeTree($tree): false|string
    {
        return json_encode($tree);
    }

    public function insertTreeAsync($treeData): void
    {
        Coroutine::create(function () use ($treeData) {
            $pg = new PostgreSQL();
            if ($pg->connect("host=db port=5432 dbname=asynctrees user=user password=password")) {
                $serializedData = $this->serializeTree($treeData);
                $sql = "INSERT INTO binary_trees (tree_data) VALUES ('$serializedData')";
                $result = $pg->query($sql);
                if ($result === false) {
                    echo "Error inserting data: " . $pg->error . PHP_EOL;
                }
            } else {
                echo "Failed to connect to PostgreSQL: " . $pg->error . PHP_EOL;
            }
        });
    }
}