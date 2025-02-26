<?php

require_once "DotEnvLoader.php";

/**
 * https://stackoverflow.com/a/3146986
 */
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

    /**
     * @throws MysqlDuplicateEntryException
     */
    public function queryDB(string $query): bool|mysqli_result
    {
        try {
            return $this->dbConnection->query($query);
        } catch (mysqli_sql_exception $exception) {
            $error_number = mysqli_errno($this->dbConnection);
            switch ($error_number) {
                case MYSQL_ERROR_DUPLICATE_ENTRY:
                    throw new MysqlDuplicateEntryException();
                default:
                    throw $exception;
            }
        }
    }
}