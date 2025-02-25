<?php

require_once('DBConfig.php');

class MySqlWrapper
{
    private string $email;
    private string $password;

    private DBConfig $connection;

    public function __construct($request)
    {
        $this->email = $request['email'];
        $this->password = $request['password'];
        $this->connection = new DBConfig();

    }

    public function parseLoginRequest(): bool
    {
        $query = "SELECT user_id FROM users WHERE email='$this->email' AND password='$this->password'";

        $result = $this->parseData($query);

        if (count($result) == 1) {
            return true;
        }
        return false;
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