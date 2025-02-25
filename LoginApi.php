<?php

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
        return new Response(
            STATUS_SUCCESS,
            DEFAULT_SUCCESS_MESSAGE,
            null
        );
    }
}

$api = new LoginApi();
echo $api->handleRequest();