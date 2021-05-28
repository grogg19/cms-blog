<?php
/**
 * Класс авторизации Auth
 */

namespace App\Auth;

use App\Controllers\ToastsController;
use App\Model\User;
use App\Controllers\UserController;
use App\Cookie\Cookie;
use App\Config;
use App\Redirect;
use Symfony\Component\HttpFoundation\Session\Session;

class Auth
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
     * @return bool
     */
    public function isAuthorized(): bool
    {
        if (!$this->session->get('authAuthorized')) {
            return false;
        }
        return true;
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function isActivated(User $user = null): bool
    {
        if($user == null) {
            $user = (new UserController())->getUserById($this->session->get('userId'));
        }
        return ($user->is_activated === 0) ? false : true;
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
     * @param $hash
     * @return User|null
     */
    public function userByHash($hash): ?User
    {
        return (new UserController())->getUserByHash($hash);
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

    /**
     * Проверка доступа прав в раздел
     * @param array $roles
     */
    public function checkPermissons(array $roles): void
    {
        if(!in_array($this->session->get('userRole'), $roles)) {
            (new ToastsController())->setToast('info', 'У вас недостаточно прав для этого действия');
            Redirect::to('/');
        }
    }

    /**
     * Проверка на роль суперпользователя
     * @param User $user
     */
    public function checkSuperUser(User $user)
    {
        if($user->role->code !== 'admin' &&  $user->is_superuser !== 1) {

            (new ToastsController())->setToast('info', 'У вас недостаточно прав для этого действия');
            Redirect::to('/admin/account');
        }
    }

}
