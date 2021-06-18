<?php


namespace App\Repository;


use App\Request\Request;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class Repository
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
     * Controller constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
        $this->request = new Request();
    }
}
