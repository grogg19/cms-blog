<?php
/**
 * Класс ListenerController
 */

namespace App\Controllers;

use App\Cookie\Cookie;
use App\Controllers\BackendControllers\AdminImageController;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ListenerController
 * @package App\Controllers
 */
class ListenerController
{
    /**
     * Метод очищает куки со списком изображений
     */
    public function ImageListener()
    {
        if(!empty(Cookie::getArray('uploadImages')) && ((new Session())->get('postBusy') !== true || session_status() !== PHP_SESSION_ACTIVE) ) {

            (new AdminImageController())->cacheImageClean();

        }
    }
}
