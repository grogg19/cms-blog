<?php
/**
 * Class ByRegex
 */
namespace App\Validate\Validation;

use App\Validate\Validation;

class ByRegex extends Validation
{

    /**
     * @var string
     */
    public string $regex;

    /**
     * IsEmpty constructor.
     * @param string $data
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
     * @return bool
     */
    private function validateByRegex(): bool
    {
        if(preg_match($this->regex, $this->data)) {
            return true;
        } else {
            return false;
        }
    }
}
