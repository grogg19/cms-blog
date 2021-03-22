<?php
/**
 * Класс ListenerController
 */

namespace App\Controllers;

use App\Cookie\Cookie;
use App\Controllers\BackendControllers\AdminImageController;
use function Helpers\printArray;
use Symfony\Component\HttpFoundation\Session\Session;
use App\DI\DI;

class ListenerController
{
    public function ImageListener()
    {
        if(!empty(Cookie::getArray('uploadImages')) && ((new Session())->get('postBusy') !== true || session_status() !== PHP_SESSION_ACTIVE) ) {

            (new AdminImageController())->cacheImageClean();

        }
    }
}
