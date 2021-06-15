<?php
/**
 * Класс Redirect
 */

namespace App;


/**
 * Class Redirect
 * @package App
 */
class Redirect
{
    /**
     * Редирект по url
     * @param $pathToRedirect
     */
    public static function to($pathToRedirect)
    {
        $url = new Url();

        if($pathToRedirect !== $_SERVER['REQUEST_URI']) {
            header("Location: " . $url->baseUrl() . $pathToRedirect );
        }
    }
}
