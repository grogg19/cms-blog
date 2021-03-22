<?php
/**
 * Class Request
 */

namespace App\Request;


class Request
{
    /**
     * @var array
     */
    private $get = [];

    /**
     * @var array
     */
    private $post = [];

    /**
     * @var array
     */
    private $request = [];

    /**
     * @var array
     */
    private $cookie = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $server = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function cookie($key = "")
    {
        if(!empty($key) && is_string($key)) {
            return $this->cookie[$key];
        }
        return $this->cookie;
    }

    /**
     * @return array
     */
    public function get($key = "")
    {
        if(!empty($key) && is_string($key)) {
            return $this->post[$key];
        }
        return $this->get;
    }

    /**
     * @return array
     */
    public function post($key = "")
    {
        if(!empty($key) && is_string($key)) {
            return (isset($this->post[$key])) ? $this->post[$key] : null;
        }
        return $this->post;
    }

    /**
     * @return array
     */
    public function files($key = "")
    {
        if(!empty($key) && is_string($key)) {
            return (isset($this->files[$key])) ? $this->files[$key] : null;
        }
        return $this->files;
    }

    /**
     * @param string $data
     * @return array|mixed
     */
    public function server($key = "")
    {
        if(!empty($key) && is_string($key)) {
            return $this->server[$key];
        }
        return $this->server;
    }
}
