<?php
/**
 * Class PublicController
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Toasts\Toast;


class PublicController extends Controller
{
    protected $toast;

    public function __construct()
    {
        parent::__construct();
        $this->toast = new Toast();
    }
}
