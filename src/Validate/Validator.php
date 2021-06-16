<?php
/**
 * Класс Validator
 */
namespace App\Validate;

use App\Exception\ValidationException;
use App\Validate\Validation\BetweenRange;
use App\Validate\Validation\ByRegex;
use App\Validate\Validation\Identity;
use App\Validate\Validation\IsEmail;
use App\Validate\Validation\IsEmpty;
use App\Validate\Validation\IsUniqueModel;
use App\Validate\Validation\IsUniquePage;
use App\Validate\Validation\UndefinedValidation;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Validator
 * @package App\Validate
 */
class Validator extends AbstractValidator
{
    private Model $model;

    /**
     * Validator constructor.
     * @param array $data
     * @param string $model
     * @param array $rules
     */
    public function __construct(array $data, string $model, array $rules = [])
    {
        parent::__construct($data, $rules);

        if($model !== '') {
            $this->model = new $model;
            if(empty($this->rules)) {
                $this->rules = $this->model->rules;
            }
        }
    }

    /**
     * Метод производит валидацию массива данных
     * @return array
     * @throws ValidationException
     */
    public function makeValidation(): array
    {
        $messagesValidations = []; // массив сообщений всех валидаций

        if(empty($this->rules)) {
            throw new ValidationException('Отсутствуют правила валидации', 503);
        }

        foreach ($this->rules as $key => $rule) {

            $result = $this->hasError($key, $rule, $messagesValidations);

            if(!empty($result)) {
                $messagesValidations['error'][$key] = $result;
            }

        }
        return $messagesValidations;
    }

    /**
     * метод принимает названия валидаций, генерирует их и при возникновении ошибки валидации создает сообщение об ошибке,
     * в результате выводит массив ошибок валидации данных конкретных полей формы
     * @param string $key
     * @param string|array $rule
     * @param array $messagesValidations
     * @return array
     */
    private function hasError(string $key,  string|array $rule, array $messagesValidations = []): array
    {
        if(is_array($rule)) {
            foreach ($rule as $r) {
                $result = $this->hasError($key, $r, $messagesValidations);

                if(!empty($result)) {
                    return $result;
                }
            }
            return [];
        }

        if(!isset($this->data[$key]) || isset($messagesValidations['error'][$key])) {
            return [];
        }

        $validation = $this->createValidation($rule, $key);

        if($validation->run() !== true) {

            return [
                'field' => $key,
                'errorMessage' => $validation->getMessage()
            ];

        } else {
            return [];
        }
    }

    /**
     * фабричный метод, генерирует валидации
     * @param string $type
     * @param string $key
     * @return Validation
     */
    protected function createValidation(string $type, string $key): Validation
    {
        $parameters = '';

        if(str_contains($type, ':')) {
            list($type, $parameters) = explode(':', $type, 2);
        }

        return match ($type) {
            'required' => new IsEmpty($this->data[$key]),
            'regex' => new ByRegex($this->data[$key], $parameters),
            'between' => new BetweenRange($this->data[$key], $parameters),
            'uniquePage' => new IsUniquePage($this->data[$key]),
            'unique' => new IsUniqueModel($this->model, $key, $this->data[$key]),
            'identityWith' => new Identity($this->data[$key], $this->data[$parameters]),
            'email' => new IsEmail($this->data[$key]),
            default => new UndefinedValidation($type)
        };
    }
}
