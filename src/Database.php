<?php

require_once '../config.php';

class Database {
    private $connection;

    public function __construct() {
        $this->connection = getDB();
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>