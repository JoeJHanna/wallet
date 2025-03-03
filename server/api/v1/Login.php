<?php

namespace api\v1;

require_once(__DIR__ . '/../../connection/MySqlWrapper.php');
require_once(__DIR__ . '/../../network/Response.php');
require_once(__DIR__ . '/../../util/Constants.php');
require_once(__DIR__ . '/../../util/ValidateString.php');
require_once(__DIR__ . '/../API.php');

use api\API;
use connection\MySqlWrapper;
use network\Response;
use util\ValidateString;
use const util\DEFAULT_ERROR_MESSAGE;
use const util\DEFAULT_SUCCESS_MESSAGE;
use const util\STATUS_SUCCESS;
use const util\STATUS_UNAUTHORIZED;
use const util\USER_NOT_FOUND_MESSAGE;


/**
 * References: https://stackoverflow.com/a/34372036
 * https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 */
class Login extends API
{
    private string $email;
    private string $password;

    public function __construct()
    {
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $this->email = $requestBody['email'];
        $this->password = $requestBody['password'];
    }

    public function handleRequest(): Response
    {
        $response = $this->handleRequestErrors($_SERVER);
        if ($response) {
            return $response;
        }
        return $this->verifyLogin();
    }

    private function verifyLogin()
    {
        $wrapper = new MySqlWrapper();
        $result = $wrapper->parseLoginRequest($this->email, $this->password);
        if (!$result) {
            return new Response(
                STATUS_UNAUTHORIZED,
                DEFAULT_ERROR_MESSAGE,
                null
            );
        }

            return new Response(
                STATUS_SUCCESS,
                DEFAULT_SUCCESS_MESSAGE,
                [$result]
            );
    }

    protected function areParamsValid(): bool
    {
        return (ValidateString::isValidEmailInput($this->email) || ValidateString::isValidPasswordInput($this->password));
    }

    protected function getAllowedMethods(): array
    {
        return ["POST"];
    }
}

$api = new Login();
echo $api->handleRequest();