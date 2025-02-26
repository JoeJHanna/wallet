<?php

require_once('DBConfig.php');
require_once('Cryptography.php');
require_once('MysqlDuplicateEntryException.php');

class MySqlWrapper
{
    private string $email;
    private string $password;

    private DBConfig $connection;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
        $this->connection = new DBConfig();

    }

    public function parseLoginRequest(): bool
    {
        $query = "SELECT password FROM users WHERE email='$this->email'";

        $result = $this->parseData($query);
        if ($result == null) {
            return false;

        }
        return Cryptography::verifyHashedPassword($this->password, $result[0]["password"]);
    }


    public function parseRegistration(): array
    {
        $data = [
            "success" => false,
            "message" => DEFAULT_ERROR_MESSAGE
        ];

        try {
            $this->connection->queryDB("INSERT INTO users (email, password) VALUES ('$this->email', '$this->password');");
            $data["success"] = true;
            $data["message"] = DEFAULT_SUCCESS_MESSAGE;
        } catch (MysqlDuplicateEntryException $exception) {
            $data["message"] = USER_ALREADY_EXISTS;
        }
        return $data;
    }
    private function parseData(string $query): array
    {
        $data = $this->connection->queryDB($query);
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