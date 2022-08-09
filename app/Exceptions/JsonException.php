<?php

namespace App\Exceptions;

class JsonException extends \Exception implements \JsonSerializable
{
    public $errors = array();

    public function __construct(string $message = "", array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function jsonSerialize()
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ];
    }
}
