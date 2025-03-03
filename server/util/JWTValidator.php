<?php

/*
 * https://www.uptimia.com/questions/how-to-check-if-a-string-is-valid-json-in-php
 */


namespace util;

require_once(__DIR__ . "/DotEnvLoader.php");

class JWTValidator
{

    private string $JWThead;

    private string $signing_key;

    public function __construct()
    {
        $env = new DotEnvLoader();
        $this->signing_key = $env->get("SIGNING_KEY");
    }


    public function validateJWT($JWT)
    {
        if (!str_contains($JWT, '.')) {
            return null;
        }
        $encoded_JWT = explode('.', $JWT);
        $encoded_JWThead = $encoded_JWT[0];
        $encoded_JWTPayload = $encoded_JWT[1];
        $encoded_JWTSignature = $encoded_JWT[2];

        $jwtHead = $this->decodeJWT($encoded_JWThead);
        $jwtPayload = $this->decodeJWT($encoded_JWTPayload);
        $jwtSignature = $this->decodeJWT($encoded_JWTSignature);

        $headParams = $this->verifyHeaderParameters($jwtHead);
        if (!$headParams) {
            return null;
        }
        $payloadParams = $this->verifyPayloadParameter($jwtPayload);
        if (!$payloadParams) {
            return null;
        }


        if ($this->validateSignature($encoded_JWThead, $encoded_JWTPayload, $jwtSignature, $headParams['algo'])) {
            return null;
        }

        return true;

    }

    public function decodeJWT($encoded_JWT)
    {

        $B64decodedJWTHead = base64_decode($encoded_JWT);

        $decodedJWTHead = utf8_decode($B64decodedJWTHead);
        $decodedJWTHead = $this->validateAndDecodeJson($decodedJWTHead);

        return $decodedJWTHead;

    }

    private function verifyHeaderParameters($JWThead)
    {
        if (!(isset($JWThead) == "alg" && isset($JWThead) == "type")) {
            return false;
        }
        return $JWThead;
    }


    private function verifyPayloadParameter($JWTPayload)
    {
        if (!(isset($JWTPayload) == "id" && isset($JWTPayload) == "role")) {
            return null;
        }
        return $JWTPayload;
    }

    private function validateSignature($encoded_JWTHead, $encoded_JWTPayload, $JWTSignature, $algo)
    {
        $signature = hash_hmac($algo, $encoded_JWTHead . "." . $encoded_JWTPayload, $this->signing_key);

        return ($signature == $JWTSignature);
    }

    function validateAndDecodeJson($string)
    {
        json_decode($string);
        if (!json_last_error() === JSON_ERROR_NONE) {
            return null;
        }
        return json_decode($string);
    }
}


$valid = new JWTValidator();
