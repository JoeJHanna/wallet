<?php

require_once("Constants.php");

class Response
{
    private $statusCode;
    private $message;
    private $data;

    public function __construct(int $statusCode, string $message, array|null $data)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
    }

    private function getBodyAsJson()
    {
        return json_encode([
            "message" => $this->message,
            "data" => $this->data
        ]);
    }

    private function get()
    {
        header(HEADERS, true, $this->statusCode);
        return $this->getBodyAsJson();
    }

    public function __toString(): string
    {
        return $this->get();
    }

}