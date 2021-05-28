<?php
/**
 * Класс AdminController
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\Controller;
use App\Controllers\ToastsController;
use App\Controllers\UserController;
use App\Cookie\Cookie;
use App\Auth\Auth;
use App\Redirect;
use App\Controllers\ListenerController;

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

    public function __construct()
    {

        parent::__construct();

        $this->auth = new Auth();

        $this->initListener();

        // Проверяем факт авторизации пользователя
        if(!$this->checkAuthorization()) {
            // если не авторизован, пользователь направляется на страницу авторизации
            Cookie::set('targetUrl', $this->request->server('REQUEST_URI'));
            $this->session->clear();
            Redirect::to('/login');
        }

    }

    /**
     * @return bool
     */
    public function checkAuthorization(): bool
    {
        if($this->auth->getHashUser() == null) {
            return false;
        }
        $this->auth->setAuthorized($this->auth->getHashUser());

        if(!$this->auth->isAuthorized()) {
            (new ToastsController())->setToast('warning', 'Вы не авторизованы');
            return false;
        }

        if(!$this->auth->isActivated()) {
            (new ToastsController())->setToast('warning', 'Ваша учетная запись недоступна');
            return false;
        } else {
            $user = (new UserController())->getUserById($this->session->get('userId'));
            (new Auth())->setUserAttributes($user);
        }
        return true;
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function checkUser()
    {

    }

    private function initListener()
    {
        (new ListenerController())->ImageListener();
    }

}
