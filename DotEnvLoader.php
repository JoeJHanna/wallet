<?php

class DotEnvLoader
{
    private $env;

    /*
     * Reference: https://stackoverflow.com/a/75621780
     */
    public function __construct()
    {
        $this->env = parse_ini_file('.env');
    }

    public function get(string $key)
    {
        if (!is_string($key) || $key ===''){
            return null;
        }
        return $this->env[$key];
    }
}
