<?php

/*
* References: https://www.php.net/manual/en/function.password-hash.php
* https://stackoverflow.com/a/17073604
*/

class Cryptography
{

    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verifyHashedPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

}
