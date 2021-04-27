<?php
/**
 * Class UserController
 */

namespace App\Controllers;

use App\Controllers\BackendControllers\UserRoleController;
use App\Controllers\Controller as Controller;
use App\Model\User;
use App\Config;

use function Helpers\checkToken;
use function Helpers\hashPassword;
use Illuminate\Database\Eloquent\Collection;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Возвращает пользователя по ID
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User
    {
        return User::find($id);
    }

    /**
     * Возвращаем модель данных текущего пользователя
     * @return User|null
     */
    public function getCurrentUser(): User|null
    {
        if(session_status() != PHP_SESSION_NONE) {
            if(!empty($this->session->get('userId'))) {
                return $this->getUserById($this->session->get('userId'));
            }
        }
        return null;
    }

    /**
     * возвращает коллекцию всех пользователей
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * Добавляет нового пользователя в БД
     * @param array $data
     * @return User
     */
    public function addUser(array $data): User
    {
        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->login = $data['login'];
        $user->password = hashPassword($data['password']);
        $user->email = $data['email'];
        $user->persist_code = $data['persist_code'];
        $user->is_activated = $data['is_activated'];
        $user->role_id = $data['role_id'];
        $user->save();

        return $user;
    }

    /**
     * Метод обновляет данные профиля пользователя в БД
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser(int $id, array $data): bool
    {
        if(checkToken()) {
            unset($data['_token']);
            return (bool) User::where('id', $id)->update($data);
        } else {
            return false;
        }
    }

    /**
     * Метод ищет пользователя по $login или $email и если находит, сверяет пароль с $password
     * и возвращает объект пользователя или FALSE при несовпадении пароля
     * @param $login
     * @param $password
     * @return User|null
     */
    public function findUser($login, $password): User|null
    {
        // Ищем пользователя с логином $login
        $userData = User::where('login', $login)
            ->orWhere('email', $login)
            ->first();

        // Если такой пользователь есть, сравниваем пароль $password с паролем пользователя
        if(!empty($userData->login)) {
            if(password_verify($password, $userData->password)) {
                // Если пароль совпал, возвращается объект этого пользователя
                return $userData;
            } else {
                // Иначе возвращается Null
                return null;
            }
        } else {
            // Иначе возвращается null
            return null;
        }
    }

    /**
     * метод возвращает пользователя найденного по хэшу
     * @param $hash
     * @return User
     */
    public function getUserByHash($hash): User
    {
        // Ищем пользователя с логином $login
        $userData = User::where('persist_code', $hash)
            ->first();
        return $userData;
    }

    /**
     * Метод генерирует и возвращает хэш
     * @param User $user
     * @return bool|string
     */
    public function makeUserHash(User $user): bool|string
    {
        return hashPassword($user->id . $user->email . $user->password);
    }

    /**
     * Метод возвращает web - адрес изображения с аватаром
     * @return string
     */
    public function getUserAvatarPath()
    {
        $path = Config::getInstance()->getConfig('avatars')['pathToUpload'];

        return SITE_ROOT . $path . DIRECTORY_SEPARATOR ;
    }

    /**
     * @return \App\Model\UserRole[]|Collection
     */
    public function getUserRoles(): Collection|array
    {
        return (new UserRoleController())->getUserRolesList();
    }

}
