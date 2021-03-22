<?php
/**
 * Class UserController
 */

namespace App\Controllers;

use App\Controllers\BackendControllers\UserRoleController;
use App\Controllers\Controller;
use App\Model\User;
use App\Config;

use function Helpers\checkToken;
use function Helpers\hashPassword;
use function Helpers\printArray;
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
    public function getCurrentUser()
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
     * @return User[]|\Illuminate\Database\Eloquent\Collection
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
            return (User::where('id', $id)->update($data)) ? true : false;
        } else {
            return false;
        }
    }

    /**
     * Метод ищет пользователя по $login или $email и если находит, сверяет пароль с $password
     * и возвращает объект пользователя или FALSE при несовпадении пароля
     * @param $login
     * @param $password
     * @return bool
     */
    public function findUser($login, $password)
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
                // Иначе возвращается FALSE
                return null;
            }
        } else {
            // Иначе возвращается пустой массив
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
    public function makeUserHash(User $user)
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

    public function getUserRoles()
    {
        return (new UserRoleController())->getUserRolesList();
    }

}
