<?php
/**
 * Класс Url
 */

namespace App;

use App\Cookie\Cookie;

/**
 * Class Url
 * @package App
 */
class Url
{
    /**
     * @var string
     */
    private $url;

    /**
     * Url constructor.
     */
    public function __construct()
    {
        $this->url = explode('?', ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'])[0];
    }

    /**
     * возвращает текущий url
     * @return string
     */
    public function url(): string
    {
        return $this->url . $_SERVER['REQUEST_URI'];
    }

    /**
     * возвращает текщий адрес сайта
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->url;
    }

    /**
     * возвращает адрес предыдущей страницы
     * @return string
     */
    public function previousUrl(): string
    {
        return $_SERVER['HTTP_REFERER'];
    }

    /**
     * Метод запоминает Url в куки и редиректит на страницу авторизации
     */
    public function toLogin() {

       Cookie::set('targetUrl', $this->url());
       Redirect::to('/login');
    }
}
