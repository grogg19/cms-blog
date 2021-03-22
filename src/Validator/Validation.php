<?php
/**
 * Class Validation
 */

namespace App\Validator;

use function Helpers\printArray;


class Validation
{
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
     * @param $dataField
     * @return bool|mixed|string
     */
    protected function validateMethod($rule, $key, $dataField)
    {
        $method = explode(":", $rule, 2);

        if ($method[0] == "required") {
            return (!$this->validateIsEmpty($dataField)) ?  "Поле должно быть заполнено" : true;
        }

        if ($method[0] == "regex" ) {
            return (!$this->validateByRegex($dataField, $method[1])) ? "В поле могут быть только латинские буквы, цифры или тире" : true;
        }

        if ($method[0] == "unique" ) {
            return (!$this->validateByUnique($key, $dataField)) ? "Такое значение уже существует" : true;
        }

        if ($method[0] == "between" ) {
            $range = explode(',',$method[1]); // Получаем параметр диапазона и преобразуем его в массив
            return (!$this->validateBetweenRange($dataField, $range)) ? "Количество символов должно быть больше или равно ". $range[0] : true;
        }

        if ($method[0] == "email" ) {
            return (!$this->validateEmail($dataField)) ? "Неправильный формат Email" : true;
        }

        if ($method[0] == "confirmed" ) {

            if(isset($_POST['password_confirm'])) {
                return (!$this->validatePasswordWithConfirmation($dataField, $_POST['password_confirm'])) ? "Пароли не совпадают" : true;
            }
            return true;
        }

        return 'Метод валидации "' . $method[0] . '" не найден';
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
        return ($this->model::where($key, $dataField)->count()) ? false : true;
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
            return ($lengthDataField >= $range[0] && $lengthDataField <= $range[1]) ? true : false;
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
        return ($password === $passwordConfirmation) ? true : false;
    }


    /**
     * Функция возвращает TRUE если переменная $dataField типа integer, иначе возвращает FALSE
     * @param $dataField
     * @return bool
     */
    public function validateIsNumber($dataField) : bool
    {
        $result = (is_numeric($dataField)) ? true : false;
        return $result;
    }

    /**
     * Функция возвращает true если переменная $dataField является адресом электронной почты, иначе возвращает false
     * @param $dataField
     * @return bool
     */
    public function validateEmail($dataField) : bool
    {
        // Если валидация email прошла успешно, filter_var() выдаст этот email с типом данных string,
        // в этом случае $result = true, иначе false

        $result = (is_string(filter_var($dataField, FILTER_VALIDATE_EMAIL))) ? true : false;

        return $result;
    }
}