<?php
/**
 * Класс авторизации Auth
 */

namespace App\Auth;

use App\Auth\AuthInterface;
use App\Model\User;
use App\Controllers\UserController;
use App\Parse\Yaml;
use App\Validator\UserFormValidation;
use App\Cookie\Cookie;
use App\View;
use App\Redirect;
use App\Url;
use App\Config;
use Symfony\Component\HttpFoundation\Session\Session;

use function Helpers\checkToken;
use function Helpers\hashPassword;
use function Helpers\generateRandomHash;
use function Helpers\printArray;

class Auth implements AuthInterface
{
    /**
     * @var
     */
    protected $hashUser;

    protected $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @return mixed
     */
    public function isAuthorized()
    {
        if ($this->session->get('authAuthorized')) {
            return $this->session->get('authAuthorized');
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getHashUser()
    {
        return Cookie::get('authUser');
    }

    /**
     * Set authorization
     * @param $hashUser
     */
    public function setAuthorized($hashUser): void
    {
        $this->session->set('authAuthorized', true);
        Cookie::set('authUser', $hashUser);
    }

    public function unAuthorize()
    {
        Cookie::delete('authUser');
        Cookie::delete('_token');
        session_destroy();
    }


    protected function userById($id)
    {
        if(!empty($id)) {
            $user = (new UserController())->getUserById($id);
            return $user;
        }
        return true;
    }

    public function userByHash($hash)
    {
        if(!empty($hash)) {
            $user = User::where('persist_code', $hash)->first();
            return $user;
        }
        return false;
    }

    public function setUserAttributes(User $user)
    {
        $this->session->set('userName', $user->first_name . ' ' . $user->last_name);
        $this->session->set('userId', $user->id);
        $this->session->set('config', Config::getInstance());
    }

    // Проверка авторизации
    public function checkRole(object $instance)
    {
        if($this->session->get('authAuthorized') == true ) {
            return $instance;
        } else {
            (new Url())->toLogin();
        }
    }

    /**
     * Метод проверяет соотвествие куки $_COOKIE['persistCode'] и persist_code объекта пользователя
     * @param User $user
     * @return bool
     */
    public function checkPersistCode(User $user)
    {
        if(isset($_COOKIE['persistCode'])) {
            return ($user->persist_code === $_COOKIE['persistCode'] ) ? true : false;
        }
        return false;
    }

}
