<?php

require_once(__DIR__ . '/../../connection/MySqlWrapper.php');
require_once(__DIR__ . '/../../network/Response.php');
require_once(__DIR__ . '/../../util/Constants.php');
require_once(__DIR__ . '/../../util/Cryptography.php');
require_once(__DIR__ . '/../../util/ValidateString.php');
require_once(__DIR__ . '/../API.php');

use api\API;
use connection\MySqlWrapper;
use network\Response;
use util\Cryptography;
use util\ValidateString;
use const util\STATUS_SUCCESS;
use const util\STATUS_UNAUTHORIZED;


class Register extends API
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
            $this->$key = $requestBody[$key];
        } else {
            $this->$key = null;
        }
    }

    public function handleRequest(): Response
    {
        $response = $this->handleRequestErrors($_SERVER);
        if ($response) {
            return $response;
        }
        $this->password = Cryptography::hashPassword($this->password);
        return $this->register();
    }

    private function register(): Response
    {
        $wrapper = new MySqlWrapper();
        $data = $wrapper->parseRegistration($this->email, $this->password);
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

    protected function areParamsValid(): bool
    {
        return ValidateString::isValidEmailInput($this->email) || ValidateString::isValidPasswordInput($this->password);
    }

    protected function getAllowedMethods(): array
    {
        return ["POST"];
    }
}

$api = new Register();
echo $api->handleRequest();