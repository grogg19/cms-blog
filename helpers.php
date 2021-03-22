<?php
/**
 *  Хелпер вывода
 */

namespace Helpers;

use App\Request\Request;
use App\Cookie\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Функция выводит в контейнере <pre> структурированную информацию о параметре $data
 * @param $data
 */
function printArray($data)
{
    echo "<pre style='  color: orange;  background-color: #000;'>";
    print_r($data);
    echo "</pre>";
}



/**
 * Функция возращает значение из массива $array с ключом $key вида "key1.key2.***.value"
 * @param array $array
 * @param $key
 * @param null $default
 * @return array|mixed|null
 */
function array_get(array $array, $key, $default = null )
{
    //  Текущий уровень
    $currentLevel =& $array;
    //  Разбиваем $key по точке на массив
    $levels = explode('.', $key);

    // Ищем в массиве $levels ключ, совпадающий с ключом в массиве $currentLevel
    foreach ($levels as $key) {
        // Если такой ключ есть и $currentLevel[$key] является массивом
        if (array_key_exists($key, $currentLevel) && is_array($currentLevel[$key])) {
            // $currentLevel становится ссылкой на $currentLevel[$key]
            $currentLevel =& $currentLevel[$key];
        } else {
            // Иначе, возвращает значение $currentLevel[$key] или значение по дефолту
            return ((empty($currentLevel[$key])) ? $default : $currentLevel[$key]);
        }
    }
    // возвращает значение $currentLevel или значение по дефолту
    return ((empty($currentLevel)) ? $default : $currentLevel);
}

/**
 * Метод возвращает массив из элементов строки REQUEST_URI
 * @return array
 */
function parseRequestUri()
{
    return explode("/", trim($_SERVER['REQUEST_URI'],'/'));
}

/**
 * Функция возвращает дату в формате d.m.Y h:i из параметра $date
 * @param $date
 * @return Значение времени и даты string
 */
function getDateTime($date) : string {
    return date('d.m.Y H:i', strtotime($date));
}

/**
 * Функция возвращает дату в формате Y.m.d  h:i:s из параметра $date
 * @param $date
 * @return Значение времени и даты string
 */
function getDateTimeForDb($date) : string {
    return date('Y.m.d  H:i:s', strtotime($date));
}

/**
 * Функция возвращает текущую дату
 * @param string $format
 * @return string
 */
function getCurrentDate($format = 'Y-m-d H:i:s'): string {
    return date($format);
}

/**
 * Функция генерирует и возвращает Токен
 * @return string
 */
function generateToken() {

    // Если  токена не существует, то
        if(Cookie::get('_token') === null) {
            // генерируем токен и записываем его в куки
            $token = hash('sha1', uniqid(rand()));
            Cookie::set('_token', $token, 3600);
            // возвращаем токен
            return $token;
        } else {
            // возвращаем токен
            return Cookie::get('_token');
        }
}

/**
 * Функция сверяет полученный токен с токеном из куки и возвращает true при совпадении иначе false
 * @return bool
 */
function checkToken() {
    if (!empty(request()->post('_token'))) {
        return (Cookie::get('_token') === request()->post('_token')) ? true : false;
    } else {
        return false;
    }
    return false;
}

/**
 * Функция хеширует пароль
 * @param $password
 * @return bool|string
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Функция верифицирует пароль
 * @param $password
 * @return bool
 */
function checkPassword($password) {
    return password_verify($password);
}

function generateRandomHash(int $length = 8) {
    $arraySymbols = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = []; // Объявляем массив $pass
    $alphaLength = strlen($arraySymbols) - 1;
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $arraySymbols[$n];
    }
    return implode($pass); //turn the array into a string
}

function request(){
    return new Request();
}

function session()
{
    return new Session();
}
