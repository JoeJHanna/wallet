<?php

namespace network;

require_once(__DIR__ . '/../util/Constants.php');

use JsonException;
use const util\HEADERS;

class Response
{
    private int $statusCode;
    private string $message;
    private ?array $data;

    public function __construct(int $statusCode, string $message, array|null $data)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @throws JsonException
     */
    private function getBodyAsJson(): bool|string
    {
        return json_encode([
            "message" => $this->message,
            "data" => $this->data
        ], JSON_THROW_ON_ERROR);
    }

    public function __toString(): string
    {
        header(HEADERS, true, $this->statusCode);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: PUT, GET, POST");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        return $this->getBodyAsJson();
    }
}