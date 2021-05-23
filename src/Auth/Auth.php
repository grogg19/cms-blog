<?php
/**
 * Класс авторизации Auth
 */

namespace App\Auth;
use App\Model\User;
use App\Controllers\UserController;
use App\Cookie\Cookie;
use App\Config;
use Symfony\Component\HttpFoundation\Session\Session;

class Auth implements AuthInterface
{
//    /**
//     * @var
//     */
//    protected $hashUser;

    protected Session $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @return false|mixed
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
     * @return null
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


    /**
     * @param $id
     * @return User|bool
     */
    protected function userById($id): User|bool
    {
        if(!empty($id)) {
            $user = (new UserController())->getUserById($id);
            return $user;
        }
        return true;
    }

    /**
     * @param $hash
     * @return bool|User
     */
    public function userByHash($hash): bool|User
    {
        if(!empty($hash)) {
            $user = User::where('persist_code', $hash)->first();
            return $user;
        }
        return false;
    }

    /**
     * @param User $user
     */
    public function setUserAttributes(User $user): void
    {
        $this->session->set('userName', $user->first_name . ' ' . $user->last_name);
        $this->session->set('userId', $user->id);
        $this->session->set('userRole', $user->role->code);
        $this->session->set('config', Config::getInstance());
    }

//    /**
//     * Проверка авторизации
//     * @param object $instance
//     * @return object
//     */
//    public function checkRole(object $instance): object
//    {
//        if($this->session->get('authAuthorized') == true ) {
//            return $instance;
//        } else {
//            (new Url())->toLogin();
//        }
//    }

    /**
     * Метод проверяет соотвествие куки $_COOKIE['persistCode'] и persist_code объекта пользователя
     * @param User $user
     * @return bool
     */
    public function checkPersistCode(User $user): bool
    {
        if(isset($_COOKIE['persistCode'])) {
            return $user->persist_code === $_COOKIE['persistCode'];
        }
        return false;
    }

}
