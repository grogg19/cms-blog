<?php
/**
 * Класс Controller
 */

namespace App\Controllers;

use App\Request\Request;
use App\Toasts\Toast;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Controller
 * @package App
 */
abstract class Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Toast
     */
    protected $toast;

    /**
     * @var
     */
    public $view;

    /**
     * @var
     */
    public $data;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
        $this->request = new Request();
        $this->toast = new Toast();
    }

}
