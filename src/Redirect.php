<?php
/**
 * Класс Redirect
 */

namespace App;

use App\Url;
use function Helpers\printArray;

/**
 * Class Redirect
 * @package App
 */
class Redirect
{
    /**
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