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
        $userRepository = new UserRepository();

        if(!$userRepository->getCurrentUser()) {
            return false;
        }

        if(in_array($userRepository->getCurrentUser()->role->code, ['admin', 'content-manager'])) {
            return true;
        }

        return false;
    }

}
