<?php


namespace App\Validate\Validation;


use App\Validate\Validation;

/**
 * Class UndefinedValidation
 * @package App\Validate\Validation
 */
class UndefinedValidation extends Validation
{
    public string $type;
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function run(): bool
    {
        $this->message = 'Неизвестный тип валидации "' . $this->type . '"';
        return false;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
