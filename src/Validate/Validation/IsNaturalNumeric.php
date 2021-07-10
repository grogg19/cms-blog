<?php

namespace App\Validate\Validation;

use App\Validate\Validation;

/**
 * Class IsEmail
 * @package App\Validate\Validation
 */
class IsNaturalNumeric extends Validation
{
    /**
     * IsEmail constructor.
     * @param string $data
     */
    public function __construct(string $data)
    {
        parent::__construct();

        $this->data = $data;
    }

    /**
     * Метод вызывает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->isNaturalNumeric();
    }

    /**
     * реализация валидации
     * @return bool
     */
    private function isNaturalNumeric(): bool
    {
        if (!preg_match('/^[0-9]+$/i', $this->data) || $this->data == 0) {
            $this->message = 'В поле должно быть целое число > 0' ;
            return false;
        }
        return true;
    }
}
