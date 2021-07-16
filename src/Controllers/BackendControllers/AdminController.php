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
use App\Request\Request;
use App\Toasts\Toast;

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
     * @var Toast
     */
    protected $toast;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth = new Auth();

        $request = new Request();
        
        $this->toast = new Toast();

        // Проверяем факт авторизации пользователя
        $check = $this->auth->checkAuthorization();

        if ($check['access'] !== 'allowed') {

            $this->toast->setToast('warning', $check['message']);

            // если не авторизован, его целевой адрес сохраняется и пользователь направляется на страницу авторизации
            Cookie::set('targetUrl', $request->server('REQUEST_URI'));

            // Чистка сессии
            session()->invalidate();

            if(!empty($request->server('HTTP_X_REQUESTED_WITH'))) {
                return json_encode(['url' => '/login']);
            } else {
                Redirect::to('/login');
            }
        }

    }

    public function __destruct()
    {
        $this->initCheckers();
    }


    /**
     * Инициализация метода дополнительных проверок
     */
    private function initCheckers()
    {
        (new ImageManager())->checkImageUploadActuality();
    }

}
