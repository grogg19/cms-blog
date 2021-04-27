<?php

namespace App\Validate\Validation;

use App\Validate\Validation;

/**
 * Class IsEmail
 * @package App\Validate\Validation
 */
class IsEmail extends Validation
{
    /**
     * IsEmail constructor.
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * Метод вызывает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->isEmail();
    }

    /**
     * @return bool
     */
    private function isEmail(): bool
    {
        if(!filter_var($this->data, FILTER_VALIDATE_EMAIL)) {
            $this->message = 'Формат Email неверный : '. $this->data ;
            return false;
        }
        return true;
    }
}
