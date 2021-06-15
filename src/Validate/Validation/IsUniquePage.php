<?php
/**
 * Class isUnique
 */
namespace App\Validate\Validation;

use App\Router;
use App\Validate\Validation;

/**
 * Class IsUniquePage
 * @package App\Validate\Validation
 */
class IsUniquePage extends Validation
{

    /**
     * IsUniquePage constructor.
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->isUniquePage();
    }

    /**
     * Проверка уникальности статической страницы
     * @return bool
     */
    private function isUniquePage(): bool
    {
        $router = require APP_DIR . '/routes/web.php';

        if(!$router instanceof Router) {
            return false;
        }

        if(!$router->isRouteExist($this->data)) {
            return true;
        };

        $this->message = 'Страница с таким URL уже существует';
        return false;
    }
}
