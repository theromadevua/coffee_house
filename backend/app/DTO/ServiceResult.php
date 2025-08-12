<?php

namespace App\DTO;

class ServiceResult
{
    private function __construct(
        private bool $success,
        private mixed $data = null,
        private string $message = ''
    ) {}

    public static function success(mixed $data = null, string $message = ''): self
    {
        return new self(true, $data, $message);
    }

    public static function failure(string $message = '', mixed $data = null): self
    {
        return new self(false, $data, $message);
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
