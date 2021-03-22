<?php
/**
 * Класс Controller
 */

namespace App\Controllers;

use App\Request\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class Controller
 * @package App
 */
abstract class Controller extends AbstractController
{

    protected $request;

    protected $session;

    public function __construct()
    {

        $this->session = new Session();
        $this->request = new Request();

    }
}
