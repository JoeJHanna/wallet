<?php

namespace api\v1;

require_once(__DIR__ . '/../../connection/MySqlWrapper.php');
require_once(__DIR__ . '/../../network/Response.php');
require_once(__DIR__ . '/../../util/Constants.php');
require_once(__DIR__ . '/../../util/ValidateString.php');

use connection\MySqlWrapper;
use network\Response;
use util\ValidateString;
use const util\DEFAULT_ERROR_MESSAGE;
use const util\DEFAULT_SUCCESS_MESSAGE;
use const util\STATUS_BAD_REQUEST;
use const util\STATUS_METHOD_NOT_ALLOWED;
use const util\STATUS_SUCCESS;
use const util\STATUS_UNAUTHORIZED;
use const util\USER_NOT_FOUND_MESSAGE;


/**
 * References: https://stackoverflow.com/a/34372036
 * https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 */
class LoginApi
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
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            return new Response(
                STATUS_METHOD_NOT_ALLOWED,
                DEFAULT_ERROR_MESSAGE,
                null);
        }

        if (!$this->areParamsValid()) {
            return new Response(
                STATUS_BAD_REQUEST,
                DEFAULT_ERROR_MESSAGE,
                null
            );
        }
        return $this->verifyLogin();
    }

    private function verifyLogin(): Response
    {
        $wrapper = new MySqlWrapper($this->email, $this->password);
        if ($wrapper->parseLoginRequest()) {
            return new Response(
                STATUS_SUCCESS,
                DEFAULT_SUCCESS_MESSAGE,
                ["token"]
            );
        }
        return new Response(
            STATUS_UNAUTHORIZED,
            USER_NOT_FOUND_MESSAGE,
            null
        );


    }

    private function areParamsValid(): bool
    {
        return !((!ValidateString::isValidEmailInput($this->email)) || (!ValidateString::isValidPasswordInput($this->password)));
    }
}

$api = new LoginApi();
echo $api->handleRequest();