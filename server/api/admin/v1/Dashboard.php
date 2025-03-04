<?php

namespace api\Admin\v1;

require_once(__DIR__ . "/../../API.php");
require_once(__DIR__ . "/../../../util/JWTValidator.php");
require_once(__DIR__ . '/../../../util/Constants.php');
require_once(__DIR__ . '/../../../network/Response.php');

use api\API;
use network\Response;
use util\JWTValidator;
use const util\DEFAULT_ERROR_MESSAGE;
use const util\DEFAULT_SUCCESS_MESSAGE;
use const util\STATUS_SUCCESS;
use const util\STATUS_UNAUTHORIZED;

class Dashboard extends API
{

    private array $payloadParam;

    public function __construct()
    {
    }

    public function handleRequest(): Response
    {

        $response = $this->handleRequestErrors($_SERVER);
        if ($response)  {
            return $response;

        }
        return $this->authenticateToken();
    }


    private function authenticateToken(): Response
    {


        if ($this->payloadParam['role'] == 1) {
            return new Response(
                STATUS_SUCCESS,
                DEFAULT_SUCCESS_MESSAGE,
                ["auth"]
            );

        }

        return new Response(
            STATUS_UNAUTHORIZED,
            DEFAULT_ERROR_MESSAGE,
            null
        );

    }


    protected function getAllowedMethods(): array
    {
        return ["GET"];
    }

    protected function areParamsValid(): bool
    {
        if (!$_COOKIE["access_token"]) {
            $receivedToken = apache_request_headers()["Authorization"];
        }
        else {
            $receivedToken = $_COOKIE["access_token"];
        }
        $tokenValidator = new JWTValidator();
        $payloadParam = $tokenValidator->validateJWT($receivedToken);
        if (!$payloadParam) {
            return false;
        }

        $this->payloadParam = $payloadParam;


        return true;
    }
}


echo (new Dashboard())->handleRequest();