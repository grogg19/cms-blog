<?php
/**
 * Класс Router
 */

namespace App;

use App\DI\DI;
use App\Exception\NotFoundException as NotFoundException;
use App\Route as Route;

/**
 * Class Router
 * @package App
 */
class Router
{
    /**
     * @var
     */
    private array $routes; // массив маршрутов

    /**
     * Метод регистрирует маршруты
     * @param $method
     * @param $uri
     * @param $callback
     */
    public function get($method, $uri, $callback)
    {
        $this->routes[] = new Route($method, $uri, $callback);
    }

    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function dispatch()
    {
        foreach ($this->routes as $route) {
            if($route->match(strtolower($_SERVER['REQUEST_METHOD']), $_SERVER['REQUEST_URI']) == true)
            {
                return $route->run();
            }
        }

        throw new NotFoundException ("Такой страницы не существует", 500);

    }
}
