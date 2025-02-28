<?php

require_once(__DIR__ . '/../../connection/MySqlWrapper.php');
require_once(__DIR__ . '/../../network/Response.php');
require_once(__DIR__ . '/../../util/Constants.php');
require_once(__DIR__ . '/../../util/Cryptography.php');
require_once(__DIR__ . '/../../util/ValidateString.php');

use connection\MySqlWrapper;
use network\Response;
use util\Cryptography;
use util\ValidateString;
use const util\DEFAULT_ERROR_MESSAGE;
use const util\STATUS_METHOD_NOT_ALLOWED;
use const util\STATUS_SUCCESS;
use const util\STATUS_UNAUTHORIZED;


class RegisterApi
{

    private string|null $email;
    private string| null $password;

    public function __construct()
    {
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $this->setVariable(requestBody: $requestBody, key: "email");
        $this->setVariable(requestBody: $requestBody, key: "password");
    }

    private function setVariable($requestBody, $key)
    {
        if (isset($requestBody[$key])) {
            $this->$key = $requestBody['email'];
        } else {
            $this->$key = null;
        }
    }

    public function handleRequest(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            return new Response(
                STATUS_METHOD_NOT_ALLOWED,
                DEFAULT_ERROR_MESSAGE,
                null);
        }

        if (!$this->areParamsValid()) {
            return new Response(
                STATUS_UNAUTHORIZED,
                DEFAULT_ERROR_MESSAGE,
                null
            );
        }

        $this->password = Cryptography::hashPassword($this->password);
        return $this->register();
    }

    private function register(): Response
    {
        $wrapper = new MySqlWrapper($this->email, $this->password);
        $data = $wrapper->parseRegistration();
        $status_code = STATUS_UNAUTHORIZED;
        if ($data["success"]) {
            $status_code = STATUS_SUCCESS;
        }
        return new Response(
            $status_code,
            $data["message"],
            null
        );
    }

    private function areParamsValid(): bool
    {
        return !((!ValidateString::isValidEmailInput($this->email)) || (!ValidateString::isValidPasswordInput($this->password)));
    }
}

$api = new RegisterApi();
echo $api->handleRequest();