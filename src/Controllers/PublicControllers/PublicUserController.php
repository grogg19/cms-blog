<?php
/**
 * Class PublicUserController
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\UserController;

class PublicUserController extends UserController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     */
    public function checkUserForComments(): bool
    {
        if(!$this->getCurrentUser()) {
            return false;
        }

        if(in_array($this->getCurrentUser()->role->code, ['admin', 'content-manager'])) {
            return true;
        }
        return false;
    }

}
