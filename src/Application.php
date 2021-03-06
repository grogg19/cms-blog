<?php
/**
 * Класс Application
 */

namespace App;

use App\Auth\Auth;
use App\Repository\UserRepository;
use App\Cookie\Cookie;
use App\Router as Router;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Database\Capsule\Manager as Capsule;
use Exception;

/**
 * Class Application
 * @package App
 */
class Application
{
    private Router $router; // Маршрутизатор

    /**
     * Application constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        generateToken();
        $this->router = $router;
        $this->initialize();
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
            if (is_object($result) && $result instanceof Renderable) {
                $this->initSession();
                // Если да, то выводим его методом render()
                echo $result->render();
            } else {
                // Если нет, то просто выводим с помощью echo
                echo $result;
            }
        } catch (Exception $e) {
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
        if ($e->getCode() === '42S02') {
            Redirect::to('/installdb');
            exit();
        }
        // Если экземпляр Renderable
        if ($e instanceof Renderable) {
            // то запускаем его метод render()
            $this->initSession();
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
            'driver'    => $dbConfig['driver'],
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

    /**
     * инициализация сессий если пользователь авторизован
     */
    public function initSession()
    {
        $session = new Session();

        // Если существует кука авторизации, стартуем сессию
        if (!empty(Cookie::get('authUser'))) {

            $currentUser = (new UserRepository())->getCurrentUser();

            // если текущего пользователя не существует, то уничтожаем авторизацию
            if ($currentUser === null) {
                (new Auth())->unAuthorize();
            }

            // если нет userId в куки, то уничтожаем авторизацию
            if ($session->get('userId') === null) {
                (new Auth())->unAuthorize();
            }
        }
    }
}
