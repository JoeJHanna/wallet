<?php
/*
 * References: https://stackoverflow.com/a/34372036
 * https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 */

require_once("MySqlWrapper.php");
require_once("Constants.php");
require_once("Response.php");
require_once("ValidateString.php");

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
        if ((!ValidateString::isValidEmailInput($this->email)) || (!ValidateString::isValidPasswordInput($this->password))) {
            return false;
        }
        return true;
    }
}

$api = new LoginApi();
echo $api->handleRequest();