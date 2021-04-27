<?php
/**
 * Class Validation
 */

namespace App\Validator;

use function Helpers\printArray;


class Validation
{
    /**
     * @var
     */
    public $model;

    /**
     * Метод проверяет поля из массива $data на соответствие условиям
     * @param $data
     * @param array $ownRules
     * @return array
     */
    public function validate($data, $ownRules = [])
    {

//        printArray($data);
//        printArray($ownRules);


        $message = [];

        $validateObject = new $this->model;
        if(count($ownRules) > 0) {
            $validateObject->rules = $ownRules;
        }

        foreach ($validateObject->rules as $key => $rule) {
            if (is_array($rule)) {

                foreach ($rule as $r) {
                    if(isset($data[$key]) && !isset($message['error'][$key])) {
                        $result = $this->validateMethod($r, $key, $data[$key]);
                        if($result !== true) {
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
        return $message;
    }

    /**
     * Метод парсит $rule и осуществляет проверку в зависимости от полученного значения
     * @param $rule
     * @param $key
     * @param $dataField
     * @return bool|\Closure|string
     */
    protected function validateMethod($rule, $key, $dataField): bool|\Closure|string
    {
        $method = explode(":", $rule, 2);

        return match ($method[0]) {
            'required' => !$this->validateIsEmpty($dataField) ?  "Поле должно быть заполнено" : true,

            'regex' => !$this->validateByRegex($dataField, $method[1]) ? "В поле могут быть только латинские буквы, цифры или тире" : true,

            'unique' => !$this->validateByUnique($key, $dataField) ? "Такое значение уже существует" : true,

            'between' => function($method, $dataField){

                $range = explode(',',$method[1]); // Получаем параметр диапазона и преобразуем его в массив
                return !$this->validateBetweenRange($dataField, $range) ? "Количество символов должно быть больше или равно ". $range[0] : true;

            },

            'email' =>!$this->validateEmail($dataField) ? "Неправильный формат Email" : true,

            'confirmed' => function($dataField) {

                if(isset($_POST['password_confirm'])) {
                    return !$this->validatePasswordWithConfirmation($dataField, $_POST['password_confirm']) ? "Пароли не совпадают" : true;
                }
                return true;

            },

            default => 'Метод валидации "' . $method[0] . '" не найден'
        };

    }

    /**
     * Метод проверяет пустоту переменной $dataField
     * @param $dataField
     * @return bool
     */
    public function validateIsEmpty($dataField) : bool
    {
        if (mb_strlen(filter_var($dataField, FILTER_SANITIZE_STRING )) != 0 && $dataField != 'undefined') {
        //if ($dataField !== "" && $dataField !== 'undefined') {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Метод проверяет содержимое переменной $dataField на соответствие шаблону регулярного выражения $regex
     * @param $dataField
     * @param $regex
     * @return bool
     */
    public function validateByRegex($dataField, $regex): bool
    {
        if(preg_match($regex, $dataField)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Метод проверяет переменную $dataField в поле $key на уникальность. Если значение в поле $key уже существует, то
     * значит $dataField не уникальна и метод вернет False
     * @param $key
     * @param $dataField
     * @return bool
     */
    public function validateByUnique($key, $dataField): bool
    {
        return !$this->model::where($key, $dataField)->count();
    }

    /**
     * Метод считает количество символов в $dataField и проверяет, входит ли это количество в разрешенный диапазон
     * @param $dataField
     * @param array $range
     * @return bool
     */
    public function validateBetweenRange($dataField, $range = [2, 255]): bool
    {
        if(!empty($dataField)) {
            $lengthDataField = mb_strlen($dataField);
            return $lengthDataField >= $range[0] && $lengthDataField <= $range[1];
        }
        return false;
    }

    /**
     * Метод проверяет идентичность пароля и подтверждения и возвращает либо TRUE либо FALSE
     * @param $password
     * @param $passwordConfirmation
     * @return bool
     */
    public function validatePasswordWithConfirmation($password, $passwordConfirmation): bool
    {
        return $password === $passwordConfirmation;
    }


    /**
     * Функция возвращает TRUE если переменная $dataField типа integer, иначе возвращает FALSE
     * @param $dataField
     * @return bool
     */
    public function validateIsNumber($dataField) : bool
    {
        return is_numeric($dataField);
    }

    /**
     * Функция возвращает true если переменная $dataField является адресом электронной почты, иначе возвращает false
     * @param $dataField
     * @return bool
     */
    public function validateEmail($dataField) : bool
    {
        // Если валидация email прошла успешно, filter_var() выдаст этот email с типом данных string,
        // в этом случае return true, иначе false

        return is_string(filter_var($dataField, FILTER_VALIDATE_EMAIL));
    }
}