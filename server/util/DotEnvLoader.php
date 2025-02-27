<?php

namespace util;
class DotEnvLoader
{
    private array|false $env;

    /*
     * Reference: https://stackoverflow.com/a/75621780
     */
    public function __construct()
    {
        $this->env = parse_ini_file(__DIR__ . '/../.env');
    }

    public function get(string $key)
    {
        if (!$this->env) {
            return null;
        }

        if ($key === '') {
            return null;
        }
        return $this->env[$key];
    }
}