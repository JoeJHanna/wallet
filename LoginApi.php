<?php
/*
 * References: https://stackoverflow.com/a/34372036
 */

require_once("MySqlWrapper.php");
require_once("Constants.php");
require_once("Response.php");

class LoginApi
{
    public function handleRequest(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            return new Response(
                STATUS_METHOD_NOT_ALLOWED,
                DEFAULT_ERROR_MESSAGE,
                null);
        }
        return $this->verifyLogin(
        );
    }

    private function verifyLogin() {
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $wrapper = new MySqlWrapper($requestBody);
        if($wrapper->parseLoginRequest()) {
            return new Response(STATUS_SUCCESS, DEFAULT_SUCCESS_MESSAGE, ["token"]);
        }
        return new Response(STATUS_UNAUTHORIZED, NOT_FOUND_MESSAGE, null);
    }
}

$api = new LoginApi();
echo $api->handleRequest();