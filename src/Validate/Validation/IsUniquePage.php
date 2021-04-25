<?php
/**
 * Class isUnique
 */
namespace App\Validate\Validation;

use App\Router;
use App\Validate\Validation;
use App\Config;

class IsUniquePage extends Validation
{

    /**
     * isUnique constructor.
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
        if(Config::getInstance()->getConfig('cms')['staticPages'] === 'files') {

            $router = require APP_DIR . '/routes/web.php';
            if($router instanceof Router) {
                if($router->isRouteExist($this->data)) {
                    $this->message = 'Страница с таким URL уже существует';
                    return false;
                };
                return true;
            }
        } else {
            $this->message = 'Реализация статических страниц в БД еще не разработана, используй файловую систему';
            return false;
        }
        return false;
    }
}
