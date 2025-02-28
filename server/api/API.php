<?php

namespace api;

use network\Response;
use const util\DEFAULT_ERROR_MESSAGE;
use const util\STATUS_BAD_REQUEST;
use const util\STATUS_METHOD_NOT_ALLOWED;

/**
 * Reference: https://www.php.net/manual/en/function.in-array.php
 */
abstract class API
{
    abstract protected function getAllowedMethods(): array;
    abstract protected function areParamsValid(): bool;

    protected function handleRequestErrors($request): ?Response
    {
        $method = $request['REQUEST_METHOD'];
        if(!in_array($method, $this->getAllowedMethods(), true)) {
            return new Response(
                STATUS_METHOD_NOT_ALLOWED,
                DEFAULT_ERROR_MESSAGE,
                null);
        };

        if (!$this->areParamsValid()) {
            return new Response(
                STATUS_BAD_REQUEST,
                DEFAULT_ERROR_MESSAGE,
                null
            );
        }
        return null;
    }
}