<?php
/**
 * Class PublicUserController
 */

namespace App\Controllers\PublicControllers;

use App\Repository\UserRepository;

class PublicUserController extends PublicController
{
    /**
     * @return bool
     */
    public function checkUserForComments(): bool
    {
        $user = (new UserRepository())->getCurrentUser();

        if($user === null) {
            return false;
        }

        if(in_array($user->role->code, ['admin', 'content-manager'])) {
            return true;
        }

        return false;
    }

}
