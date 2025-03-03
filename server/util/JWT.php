<?php


/*
 * References: https://auth0.com/docs/secure/tokens/json-web-tokens/json-web-token-structure
 * https://stackoverflow.com/a/73271804
 * https://www.loginradius.com/blog/engineering/guest-post/securing-php-api-with-jwt/
 * https://stackoverflow.com/a/53527119
 * https://stackoverflow.com/a/62095056
 * https://jwt-keys.21no.de/
 */

namespace util;

require_once(__DIR__ . "/DotEnvLoader.php");

class JWT
{
    private string $user_id;
    private string $role;

    private string $signingKey;

    private string $hashingAlgo;

    public function __construct($user_id, $role)
    {
        $this->user_id = $user_id;
        $this->role = $role;
        $env = new DotEnvLoader();
        $this->signingKey = $env->get("SIGNING_KEY");
        $this->hashingAlgo = $env->get("JWT_HASHING_ALGO");
    }

    private function getHeader(): string
    {
        $header = [
            'alg' => $this->hashingAlgo,
            'typ' => "JWT"
        ];

        return $this->base64UrlEncode($header);

    }


    private function getPayload(): string
    {
        $payload = [
            'id' => "$this->user_id",
            'role' => "$this->role"
        ];
        return $this->base64UrlEncode($payload);
    }

    private function getSignature()
    {
        $signature = hash_hmac($this->hashingAlgo, $this->getHeader() . "." . $this->getPayload(), $this->signingKey);
        return $this->base64UrlEncode($signature);
    }

    public function getJWT()
    {
        return $this->getHeader() . '.' . $this->getPayload() . '.' . $this->getSignature();
    }

    private function base64UrlEncode($string): string
    {
        $string = json_encode($string);
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }

}