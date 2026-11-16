<?php
namespace App\Http;

class ValidationException extends \RuntimeException
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct('The given data was invalid.');
        $this->errors = $errors;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
