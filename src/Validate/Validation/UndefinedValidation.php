<?php


namespace App\Validate\Validation;


use App\Validate\Validation;

class UndefinedValidation extends Validation
{
    public function run(): bool
    {
        $this->message = 'Неизвестный тип валидации';
        dump($this->getMessage());
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
