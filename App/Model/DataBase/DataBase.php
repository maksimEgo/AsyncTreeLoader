<?php

namespace App\Model\DataBase;

use Swoole\Coroutine\PostgreSQL;

class DataBase
{
    protected ?PostgreSQL $db = null;

    public function connect(): void
    {
        $this->db = new PostgreSQL();
        if (!$this->db->connect("host=db port=5432 dbname=asynctrees user=user password=password")) {
            echo 'Connection error: ' . $this->db->error . PHP_EOL;
        } else {
            echo 'Connected' . PHP_EOL;
        }
    }

    public function query(string $sql)
    {
        $result = $this->db->query($sql);
        if ($result === false) {
            echo "Query error: " . $this->db->error . PHP_EOL;
        }
        return $result;
    }

    public function getLastError()
    {
        return $this->db->error;
    }
}