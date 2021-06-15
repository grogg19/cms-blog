<?php
/**
 * Class ByRegex
 */
namespace App\Validate\Validation;

use App\Validate\Validation;

/**
 * Class ByRegex
 * @package App\Validate\Validation
 */
class ByRegex extends Validation
{

    /**
     * @var string
     */
    public string $regex;

    /**
     * ByRegex constructor.
     * @param string $data
     * @param string $regex
     */
    public function __construct(string $data, string $regex)
    {
        $this->data = $data;
        $this->regex = $regex;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->validateByRegex();
    }

    /**
     * реализация валидации
     * @return bool
     */
    private function validateByRegex(): bool
    {
        if(preg_match($this->regex, $this->data)) {
            return true;
        } else {
            $this->message = "Данные введены неверно";
            return false;
        }
    }
}
