<?php
/**
 * Класс Cookie для работы с куки
 */

namespace App\Cookie;

/**
 * Class Cookie
 * @package App\Cookie
 */
class Cookie
{
    /**
     * Создание куки
     * @param $key
     * @param $value
     * @param int $time
     */
    public static function set($key, $value, $time = 31536000)
    {
        setcookie($key, $value, time() + $time, '/') ;
    }

    /**
     * Создаем куки из массива
     * @param $key
     * @param array $value
     * @param int $time
     */
    public static function setArray($key, array $value, $time = 31536000)
    {
        setcookie($key, serialize($value), time() + $time, '/') ;
    }

    /**
     * Получаем куки по ключу
     * @param $key
     * @return string|null
     */
    public static function get($key): string|null
    {
        if ( isset($_COOKIE[$key]) ) {
            return $_COOKIE[$key];
        }
        return null;
    }

    /**
     * Получаем массив в куки и десериализуем его
     * @param $key
     * @return array
     */
    public static function getArray($key) : array
    {
        if ( isset($_COOKIE[$key]) ) {
            return unserialize($_COOKIE[$key]);
        }
        return [];
    }


    /**
     * Удаляем куку подключу
     * @param $key
     */
    public static function delete($key): void
    {
        if ( isset($_COOKIE[$key]) ) {
            self::set($key, '', -3600);
            unset($_COOKIE[$key]);
        }
    }
}
