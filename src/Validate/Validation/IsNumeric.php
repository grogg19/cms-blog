<?php

namespace App\Validate\Validation;

use App\Validate\Validation;

/**
 * Class IsEmail
 * @package App\Validate\Validation
 */
class IsNumeric extends Validation
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
        return $this->isNumeric();
    }

    /**
     * реализация валидации
     * @return bool
     */
    private function isNumeric(): bool
    {
        if(!filter_var($this->data, FILTER_SANITIZE_NUMBER_INT)) {
            $this->message = 'В поле должно быть целое число > 0' ;
            return false;
        }
        return true;
    }
}
