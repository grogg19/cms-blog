<?php
/**
 *  Класс UserRoleController
 */

namespace App\Controllers\BackendControllers;

use App\Model\UserRole;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserRoleController
 * @package App\Controllers\BackendControllers
 */
class UserRoleController extends AdminController
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
