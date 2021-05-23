<?php
/**
 *  Класс UserRoleController
 */

namespace App\Controllers\BackendControllers;

use App\Model\UserRole;
use Illuminate\Database\Eloquent\Collection;


class UserRoleController extends AdminController
{
    /**
     * @return Collection
     */
    public function getUserRolesList(): Collection
    {
        return UserRole::all();
    }
}
