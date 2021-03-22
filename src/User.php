<?php
/**
 * Класс User
 */

namespace App\Users;

use App\Model\User as ModelUser;

/**
 * Class User
 * @package App\Users
 */
class User
{
    /**
     * Якобы уже имеющиеся пользователи в БД
     * @var array
     */
    public $usersIds = [1, 2, 3, 4, 5, 6];

    /**
     * Имитация загрузки пользователя из БД
     * Метод проверяет наличие $id в массиве $usersIds и если значение отсутствует, то выбрасывает исключение
     * @param $id
     * @throws \Exception
     */
    public function load($id)
    {
        // Если $id пользователя нет в массиве $this->usersIds
        if (!in_array($id, $this->usersIds)) {
            // то выбрасываем исключение
            throw new \Exception('Данного пользователя нет в базе данных', 2);
        }
    }

    /**
     * Метод имитирует запись в БД
     * @param $data
     * @throws \Exception
     */
    public function save($data)
    {
        if (rand(0, 1) == 0) {
            throw new \Exception('Сохранить пользователя <b>' . $data['name'] . '</b> не удалось.', 2);
        } else {
            throw new \Exception('Пользователь <b>' . $data['name'] . '</b> успешно сохранён.', 1);
        }
    }

    /**
     * @param $login
     * @param $password
     * @return bool
     */
    public function getUserByLoginAndPassword($login, $password)
    {
        return;
    }

    /**
     * Проверяет, существует занят ли данный логин $login и возвращает True или False
     * @param $login
     * @return bool
     */
    public function isUserLoginExists($login): bool
    {
        return ModelUser::find('login', $login) ? true : false ;
    }

}
