<?php
/**
 * Класс AbstractValidator
 */
namespace App\Validate;

abstract class AbstractValidator
{
    /**
     * @var array
     */
    public array $data;

    /**
     * @var array
     */
    public array $rules;

    /**
     * AbstractValidator constructor.
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules = [])
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * @param string $type
     * @param string $key
     * @return Validation
     */
    abstract protected function createValidation(string $type, string $key): Validation;
}
