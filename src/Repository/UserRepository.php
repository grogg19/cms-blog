<?php

namespace App\Repository;

use App\Auth\Auth;
use App\Config;
use App\Model\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class UserRepository
 * @package App\Repository
 */
class UserRepository extends Repository
{
    /**
     * Возвращает пользователя по ID
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Возвращаем модель данных текущего пользователя
     * @return User|null
     */
    public function getCurrentUser()
    {
        if(session_status() == 1) {
            return null;
        }
        if(!empty($this->session->get('userId'))) {

            return $this->getUserById($this->session->get('userId'));

        }
        return null;
    }

    /**
     * возвращает коллекцию всех пользователей кроме супер-пользователя и текущего админа
     * @param string $sortDirection
     * @param string $quantity
     * @return LengthAwarePaginator|Collection
     */
    public function getAllUsers(string $sortDirection = 'asc', string $quantity = '20')
    {
        if($quantity !== 'all') {

            $page = empty($this->request->get('page')) ? 1 : $this->request->get('page');

            return User::where('is_superuser', 0)
                //->where('id', '!=', $this->getCurrentUser()->id)
                ->orderBy('is_activated', $sortDirection)
                ->paginate($quantity, '*', 'page', $page);
        }

        return User::where('is_superuser', 0)
            ->orderBy('is_activated', $sortDirection)
            ->get();
    }

    /**
     * Метод ищет пользователя по $login или $email и если находит, сверяет пароль с $password
     * и возвращает объект пользователя или FALSE при несовпадении пароля
     * @param $email
     * @param $password
     * @return User|null
     */
    public function findUser($email, $password)
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
     * Метод генерирует и возвращает хэш
     * @param User $user
     * @return string
     */
    public function makeUserHash(User $user): string
    {
        return hashPassword($user->id . $user->email . $user->password);
    }

    /**
     * Метод возвращает web - адрес каталога с аватаром
     * @return string
     */
    public function getUserAvatarPath(): string
    {
        $path = Config::getInstance()->getConfig('avatars')['pathToUpload'];

        return SITE_ROOT . $path . DIRECTORY_SEPARATOR ;
    }

    /**
     * Метод возвращает локальный - адрес каталога с аватаром
     * @return string
     */
    public function getUserAvatarRootPath(): string
    {
        $path = Config::getInstance()->getConfig('avatars')['pathToUpload'];

        return APP_DIR . $path . DIRECTORY_SEPARATOR ;
    }

    /**
     * Возвращает коллекцию ролей пользователя
     * @return Collection
     */
    public function getUserRoles(): Collection
    {
        return (new UserRoleRepository())->getUserRolesList();

    }
}
