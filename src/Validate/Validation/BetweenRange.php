<?php
/**
 * Класс валидации BetweenRange
 */
namespace App\Validate\Validation;

use App\Validate\Validation;

class BetweenRange extends Validation
{
    /**
     * @var string
     */
    public string $range;

    /**
     * IsEmpty constructor.
     * @param string $data
     */
    public function __construct(string $data, string $range)
    {
        $this->data = $data;
        $this->range = $range;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->validateInRange();
    }

    /**
     * @return bool
     */
    private function validateInRange(): bool
    {
        $lengthDataField = mb_strlen($this->data); // длина строки

        if(!in_array($lengthDataField, range($this->range[0], $this->range[1]))) {
            $this->message = 'Значение должно быть от ' . $this->range[0] . ' до ' . $this->range[1];
            return false;
        }
        return true;
    }
}
