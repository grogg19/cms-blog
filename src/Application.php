<?php
/**
 * Класс Application
 */

namespace App;

use App\Controllers\BackendControllers\AdminImageController;
use App\Controllers\PublicControllers\StaticPagesController;
use App\Cookie\Cookie;
use App\Exception\NotFoundException;
use App\Router as Router;
use App\StaticPages\FilesList;
use App\StaticPages\PageList;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\DI\DI;

use function Helpers\printArray;


/**
 * Class Application
 * @package App
 */
class Application
{
    private $router; // Маршрутизатор

    /**
     * Application constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->getRoutesFromStaticPages();
        $this->initialize();
        $this->initSession();
    }

    /**
     * Метод проверяет результат метода dispatch.
     * Если передан объект-потомок Renderable, то он выводится методом render(),
     * иначе выводим результат с помощью echo
     */
    public function run()
    {
        try {
            $result = $this->router->dispatch();
            // проверяем, является ли экземпляр потомком Renderable
            if(is_object($result) && $result instanceof Renderable) {
                // Если да, то выводим его методом render()
                $result->render();
            } else {
                // Если нет, то просто выводим с помощью echo
                echo $result;
            }
        } catch (\Exception $e) {
            // при возникновении исключения запускаем метод renderException()
            $this->renderException($e);
        }
    }

    /**
     * При возникновении исключения метод, выводит шаблон исключения
     * @param $e
     */
    public function renderException($e)
    {
        // Если экземпляр Renderable
        if($e instanceof Renderable) {
            // то запускаем его метод render()
            $e->render();
        } else {
            // Иначе выводим сообщение исключения
            echo $e->getMessage();
        }
    }

    /**
     * Подключение к БД
     */
    public function initialize()
    {
        // получаем параметры для БД
        $dbConfig = Config::getInstance()->getConfig("db");

        // Создаем экземпляр
        $capsule = new Capsule;

        // Добавляем подключение к БД
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $dbConfig['host'],
            'database'  => $dbConfig['database'],
            'username'  => $dbConfig['username'],
            'password'  => $dbConfig['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

    }

    public function initSession()
    {
        $this->session = new Session();

        if(!empty(Cookie::get('authUser'))) {
            $this->session->start();
        }
    }


    /**
     * @throws NotFoundException
     */
    private function getRoutesFromStaticPages()
    {
        $pages = (new StaticPagesController())->getStaticPages();

        if(is_array($pages)) {
            foreach ($pages as $url => $page) {
                $this->router->get('get', $url, 'Controllers\PublicControllers\StaticPagesController@index');
            }
        }

    }

}
