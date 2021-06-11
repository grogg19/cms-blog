<?php
/**
 * Class UserController
 */

namespace App\Controllers;

use App\Auth\Auth;
use App\Controllers\BackendControllers\UserRoleController;
use App\Controllers\Controller as Controller;
use App\Model\User;
use App\Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use function Helpers\checkToken;
use function Helpers\hashPassword;

/**
 * Class UserController
 * @package App\Controllers
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     */
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
    public function getCurrentUser(): ?User
    {
        if(session_status() == PHP_SESSION_NONE) {
            return null;
        }
        if(!empty($this->session->get('userId'))) {
            return $this->getUserById($this->session->get('userId'));
        }
        return null;
    }

    /**
     * возвращает коллекцию всех пользователей кроме супер-пользователя
     * @param string $sortDirection
     * @param string $quantity
     * @return LengthAwarePaginator|Collection
     */
    public function getAllUsers(string $sortDirection = 'asc', string $quantity = '20'): LengthAwarePaginator|Collection
    {
        if($quantity !== 'all') {

            $page = empty($this->request->get('page')) ? 1 : $this->request->get('page');

            return User::where('is_superuser', 0)
                ->orderBy('is_activated', $sortDirection)
                ->paginate($quantity, '*', 'page', $page);
        }

        return User::where('is_superuser', 0)
            ->orderBy('is_activated', $sortDirection)
            ->get();
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
        $user->password = hashPassword($data['password']);
        $user->email = $data['email'];
        $user->persist_code = $data['persist_code'];
        $user->is_activated = $data['is_activated'];
        $user->role_id = 3;
        $user->save();

        return $user;
    }

    /**
     * Метод обновляет данные профиля пользователя в БД
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function updateUser(User $user, array $data): bool
    {
        if(!checkToken()) {
            return false;
        }

        if(!empty($data['password'])) {
            $data['password'] = hashPassword($data['password']);
        }

        unset($data['_token']);
        $user->update($data);

        if($this->session->get('userId') === $user->id) {
            (new Auth())->setUserAttributes($user);
        }

        return true;
    }

    /**
     * Метод ищет пользователя по $login или $email и если находит, сверяет пароль с $password
     * и возвращает объект пользователя или FALSE при несовпадении пароля
     * @param $email
     * @param $password
     * @return User|null
     */
    public function findUser($email, $password): ?User
    {
        // Ищем пользователя с логином $login
        $userData = User::where('email', $email)
            ->first();

        if (empty(($userData->email))) {
            return null;
        }

        if (password_verify($password, $userData->password)) {
            // Если пароль совпал, возвращается объект этого пользователя
            return $userData;
        }

        return null;
    }

    /**
     * метод возвращает пользователя найденного по хэшу
     * @param $hash
     * @return User|null
     */
    public function getUserByHash($hash): ?User
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
    public function getUserAvatarPath(): string
    {
        $path = Config::getInstance()->getConfig('avatars')['pathToUpload'];

        return SITE_ROOT . $path . DIRECTORY_SEPARATOR ;
    }

    /**
     * Возвращает коллекцию ролей пользователя
     * @return Collection
     */
    public function getUserRoles(): Collection
    {
        return (new UserRoleController())->getUserRolesList();
    }

}
