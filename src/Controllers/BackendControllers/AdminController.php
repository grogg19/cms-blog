<?php
/**
 * Класс AdminController
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\Controller;
use App\Cookie\Cookie;
use App\Auth\Auth;
use App\Image\ImageManager;
use App\Redirect;

/**
 * Class AdminController
 * @package App\Controllers\BackendControllers
 */
class AdminController extends Controller
{

    /**
     * @var Auth
     */
    protected $auth;



    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth = new Auth();

        $this->initCheckers();

        // Проверяем факт авторизации пользователя
        if(!$this->auth->checkAuthorization()) {

            // если не авторизован, пользователь направляется на страницу авторизации
            Cookie::set('targetUrl', $this->request->server('REQUEST_URI'));

            $this->session->clear();

            Redirect::to('/login');
        }
    }


    /**
     * Инициализация метода дополнительных проверок
     */
    private function initCheckers()
    {
        (new ImageManager())->checkImageUploadActuality();
    }

}
