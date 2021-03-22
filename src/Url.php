<?php
/**
 * Класс Url
 */

namespace App;


use App\Redirect;
use function Helpers\printArray;

class Url
{
    private $url;

    public function __construct()
    {
        $this->url = explode('?', ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'])[0];
    }

    public function url()
    {
        return $this->url . $_SERVER['REQUEST_URI'];
    }

    public function baseUrl()
    {
        return $this->url;
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Метод запоминает Url в сессии и редиректит на страницу авторизации
     */
    public function toLogin() {

       $_SESSION['targetUrl'] = $this->url();

        //header('Location: '. (new Url)->baseUrl() . '/login');
        Redirect::to('/login');
    }
}