<?php
/**
 *  Класс UserRoleRepository
 */

namespace App\Repository;

use App\Model\UserRole;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserRoleRepository
 * @package App\Repository
 */
class UserRoleRepository extends Repository
{
    /**
     * возвращает коллекцию ролей
     * @return Collection
     */
    public function getUserRolesList(): Collection
    {
        return UserRole::all();
    }
}
