<?php
/**
 * Класс авторизации Auth
 */

namespace App\Auth;

use App\Model\User;
use App\Repository\UserRepository;
use App\Cookie\Cookie;
use App\Config;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Auth
 * @package App\Auth
 */
class Auth
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * Auth constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        if ($this->session->get('authAuthorized')) {
            return true;
        }
        return false;
    }

    /**
     * проверяет забанен ли пользователь
     * @param User|null $user
     * @return bool
     */
    public function isActivated(User $user = null): bool
    {
        if ($user !== null) {
            return !($user->is_activated === 0);
        }

        if ($this->session->get('userId') === null) {
            $user = (new UserRepository())->getUserByHash($this->getHashUser());
        } else {
            $user = (new UserRepository())->getUserById($this->session->get('userId'));
        }
        return !($user === null || $user->is_activated === 0 );
    }

    /**
     * Возвращает хэш пользователя из куки
     * @return string
     */
    public function getHashUser(): string
    {
        return Cookie::get('authUser');
    }

    /**
     * устанавливает хэш в куки и статус авторизации пользователя в TRUE
     * @param $hashUser
     */
    public function setAuthorized($hashUser): void
    {
        $this->session->set('authAuthorized', true);
        Cookie::set('authUser', $hashUser);
    }

    /**
     * деавторизация пользователя, удаляет куки и сессию
     */
    public function unAuthorize()
    {
        Cookie::delete('authUser');
        Cookie::delete('_token');
        $this->session->invalidate();
    }

    /**
     * Возвращает пользователя по хэшу
     * @param $hash
     * @return User|null
     */
    public function userByHash($hash): ?User
    {
        return (new UserRepository())->getUserByHash($hash);
    }

    /**
     * устанавливает аттрибуты пользователя в сессию
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
        if (isset($_COOKIE['persistCode'])) {
            return $user->persist_code === $_COOKIE['persistCode'];
        }
        return false;
    }

    /**
     * Проверка доступа прав в раздел
     * @param array $roles
     * @return bool
     */
    public function checkPermissons(array $roles): bool
    {
        if (in_array($this->session->get('userRole'), $roles)) {
           return true;
        }
        return false;
    }

    /**
     * Проверка на роль суперпользователя
     * @param User $user
     * @return bool
     */
    public function checkSuperUser(User $user): bool
    {
        if ($user->role->code === 'admin' &&  $user->is_superuser === 1) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function checkAuthorization(): array
    {
        if ($this->getHashUser() == null) {
            return [
                'access' => 'denied',
                'message' => 'Вы не авторизованы'
            ];
        }

        $this->setAuthorized($this->getHashUser());

        if (!$this->isAuthorized()) {
            return [
                'access' => 'denied',
                'message' => 'Вы не авторизованы'
            ];
        }

        if (!$this->isActivated()) {
            return [
                'access' => 'denied',
                'message' => 'Ваша учетная запись недоступна'
            ];
        } else {
            $user = (new UserRepository())->getUserById($this->session->get('userId'));
            $this->setUserAttributes($user);
            return [
                'access' => 'allowed'
            ];
        }

    }

}
