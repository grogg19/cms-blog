<?php
/**
 * Класс Validator
 */
namespace App\Validate;

use App\Validate\Validation\IsEmpty;

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
     * @return Validation
     */
    public function makeValidation()
    {
        foreach ($this->rules as $key => $rule) {
            if (is_array($rule)) {
                foreach ($rule as $r) {
                    if(isset($data[$key]) && !isset($message['error'][$key])) {

                        $result = $this->validateMethod($r, $key, $data[$key]);

                        $validation = $this->createValidation($r);

                        if($validation->run() !== true) {
                            $message['error'][$key] = [
                                'field' => $key,
                                'errorMessage' => $result
                            ];
                        }
                    }
                }
            } else {
                if(isset($data[$key]) && !isset($message['error'][$key])){
                    $result = $this->validateMethod($rule, $key, $data[$key]);
                    if($result !== true) {
                        $message['error'][$key] = [
                            'field' => $key,
                            'errorMessage' => $result
                        ];
                    }
                }
            }
        }


        return $validation;
    }


    /**
     * @param string $type
     * @return Validation
     */
    protected function createValidation(string $type): Validation
    {
        return match ($type) {
            'required' => new IsEmpty($this->data)
        };
    }
}
