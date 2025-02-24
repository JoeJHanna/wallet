<?php

require_once "DotEnvLoader.php";

class DBConfig
{
    private Mysqli $dbConnection;

    public function __construct()
    {
        $env = new DotEnvLoader();

        $this->dbConnection = new Mysqli("localhost", $env->get("DB_USER"), $env->get("DB_PASSWORD"), $env->get("DB_TABLE"));

        if ($this->dbConnection->error) {
            echo "Error connecting to Database";
        }
    }

    public function queryDB(string $query): bool|mysqli_result
    {
        return $this->dbConnection->query($query);
    }
}