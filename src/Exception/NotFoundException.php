<?php
/**
 * Класс NotFoundException
 */

namespace App\Exception;

use App\Exception\HttpException as HttpException;
use App\Renderable as Renderable;
use App\Repository\UserRepository;
use App\View;
use Throwable;

/**
 * Class NotFoundException
 * @package App\Exception
 */
class NotFoundException extends HttpException implements Renderable
{
    /**
     * @var string
     */
    protected $message;

    /**
     * NotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Страница не найдена", $code = 0, Throwable $previous = null)
    {
        $this->message = $message;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Метод выводит шаблон 404.php
     */
    public function render(): void
    {
        $data['message'] = $this->message;
        $data['title'] = 'Страница не найдена';

        $data['user'] = (new UserRepository())->getCurrentUser();


        (new View('404', $data))->render();
    }
}
