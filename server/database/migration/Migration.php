#! /usr/bin/php
<?php

namespace database\migration;

use connection\DBConfig;
use util\DotEnvLoader;

require_once(__DIR__ . '/../../connection/DBConfig.php');
require_once(__DIR__ . '/../../util/DotEnvLoader.php');

class Migration
{
    private DBConfig $connection;
    private DotEnvLoader $env;

    public function __construct()
    {
        $this->connection = new DBConfig(isMigration: true);
        $this->env = new DotEnvLoader();

    }

    private function createDatabase(): void
    {
        $query = "CREATE DATABASE IF NOT EXISTS " . $this->env->get("DB_NAME") . ";";
        $this->connection->queryDB($query);
    }

    private function useDatabase(): void
    {
        $query = "USE " . $this->env->get("DB_NAME") . ";";
        $this->connection->queryDB($query);
    }

    private function createUserTable(): void
    {
        $query = "CREATE TABLE IF NOT EXISTS users (
            user_id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(50) NOT NULL,
            password VARCHAR(50) NOT NULL
        )";
        $this->connection->queryDB($query);
    }

    public function run(): void
    {
        $this->createDatabase();
        $this->useDatabase();
        $this->createUserTable();
    }
}

$migration = new Migration();
$migration->run();