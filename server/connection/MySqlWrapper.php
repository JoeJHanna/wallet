<?php

namespace connection;

require_once(__DIR__ . '/../exception/MysqlDuplicateEntryException.php');
require_once(__DIR__ . '/../util/Constants.php');
require_once(__DIR__ . '/../util/Cryptography.php');
require_once (__DIR__ . '/../util/JWT.php');

use exception\MysqlDuplicateEntryException;
use util\Cryptography;
use util\JWT;
use const util\DEFAULT_ERROR_MESSAGE;
use const util\DEFAULT_SUCCESS_MESSAGE;
use const util\USER_ALREADY_EXISTS;

require_once("DBConfig.php");

class MySqlWrapper
{
    private DBConfig $connection;

    public function __construct()
    {
        $this->connection = new DBConfig();

    }

    public function parseLoginRequest($email, $password): ?string
    {
        $query = "SELECT user_id, password FROM users WHERE email='$email'";

        $result = $this->parseData($query);
        if ($result == null) {
            return null;

        }
        if (Cryptography::verifyHashedPassword($password, $result[0]["password"])) {
            return (new  JWT($result[0]["user_id"], '1'))->getJWT();
        }
        return  null;
    }


    public function parseRegistration($email, $password): array
    {
        $data = [
            "success" => false,
            "message" => DEFAULT_ERROR_MESSAGE
        ];

        try {
            $this->connection->queryDB("INSERT INTO users (email, password) VALUES ('$email', '$password');");
            $data["success"] = true;
            $data["message"] = DEFAULT_SUCCESS_MESSAGE;
        } catch (MysqlDuplicateEntryException) {
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