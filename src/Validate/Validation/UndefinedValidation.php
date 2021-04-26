<?php


namespace App\Validate\Validation;


use App\Validate\Validation;

/**
 * Class UndefinedValidation
 * @package App\Validate\Validation
 */
class UndefinedValidation extends Validation
{
    public function run(): bool
    {
        $this->message = 'Неизвестный тип валидации';
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
