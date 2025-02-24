<?php

require_once "DotEnvLoader.php";

class DBConfig
{
    private $dbConnection;

    public function __construct()
    {
        $env = new DotEnvLoader();

        $this->dbConnection = new Mysqli("localhost", $env->get("DB_USER"), $env->get("DB_PASSWORD"), $env->get("DB_TABLE"));

        if ($this->dbConnection->error) {
            echo "Error connecting to Database";
        }
    }

    public function queryDB(string $query): array
    {
        $results = $this->dbConnection->query($query);
        return $this->parseData($results);
    }

    private function parseData(mysqli_result $data): array
    {
        $rowsNumber = $data->num_rows;
        $parsedData = [];
        for ($i = 0; $i < $rowsNumber; $i++) {
            $data->data_seek($i);
            $row = $data->fetch_array(MYSQLI_ASSOC);
            $parsedData[] = $row;
        }
        return $parsedData;
    }
}