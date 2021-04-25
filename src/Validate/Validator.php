<?php
/**
 * Класс Validator
 */
namespace App\Validate;

use App\Validate\Validation\ByRegex;
use App\Validate\Validation\IsEmpty;
use App\Validate\Validation\IsUniquePage;
use App\Validate\Validation\UndefinedValidation;

class Validator extends AbstractValidator
{
    /**
     * Validator constructor.
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules = [])
    {
        parent::__construct($data, $rules);
    }

    /**
     * @return array
     */
    public function makeValidation(): array
    {
        $messagesValidations = []; // массив сообщений всех валидаций

        foreach ($this->rules as $key => $rule) {
            if (is_array($rule)) {
                foreach ($rule as $r) {
                    if(isset($this->data[$key]) && !isset($messagesValidations['error'][$key])) {

                        $validation = $this->createValidation($r, $key);

                        if($validation->run() !== true) {
                            $messagesValidations['error'][$key] = [
                                'field' => $key,
                                'errorMessage' => $validation->getMessage()
                            ];
                        }
                    }
                }
            } else {
                if(isset($this->data[$key]) && !isset($messagesValidations['error'][$key])){

                    $validation = $this->createValidation($rule, $key);

                    if($validation->run() !== true) {
                        $messagesValidations['error'][$key] = [
                            'field' => $key,
                            'errorMessage' => $validation->getMessage()
                        ];
                    }
                }
            }
        }

        return $messagesValidations;
    }

    /**
     * @param string $type
     * @param string $key
     * @return Validation
     */
    protected function createValidation(string $type, string $key): Validation
    {
        if(str_contains($type, ':')) {
            list($type, $parameters) = explode(':', $type, 2);
        }

        return match ($type) {
            'required' => new IsEmpty($this->data[$key]),
            'regex' => new ByRegex($this->data[$key], $parameters),
            'uniquePage' => new IsUniquePage($this->data[$key]),
            default => new UndefinedValidation()
        };
    }
}
