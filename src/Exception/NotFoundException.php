<?php
/**
 * Класс NotFoundException
 */

namespace App\Exception;

use App\Exception\HttpException as HttpException;
use App\Renderable as Renderable;
use App\View as View;

/**
 * Class NotFoundException
 * @package App\Exception
 */
class NotFoundException extends HttpException implements Renderable
{
    /**
     * Метод выводит шаблон 404.php
     */
    public function render()
    {
        $data['message'] = 'Страница не найдена';
        $data['title'] = 'Такой страницы не существует';
        (new View('index', ['view' => '404', 'data' => $data]))->render();
    }
}
