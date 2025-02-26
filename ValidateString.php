<?php

/*
 * References: https://www.php.net/manual/en/filter.constants.php
 * https://mailtrap.io/blog/php-email-validation/
 * https://regex101.com/
 * https://stackoverflow.com/q/12896985
 * */

class ValidateString
{

    public static function isValidEmailInput($email): bool
    {
        return (filter_var($email, FILTER_VALIDATE_EMAIL));
    }


    public static function isValidPasswordInput($password): bool
    {
        if (preg_match('/[(){}[\]\|`¬¦! \/\\"£\$%\^&\*"<>:;#~\-\+=,@\.\']/u', $password)) {
            return false;
        }
        return true;
    }
}