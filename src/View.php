<?php
/**
 * Класс View
 */

namespace App;

use App\Twig\TemplatesBlocksExtension;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class View
 * @package App
 */
class View implements Renderable
{
    /**
     * @var string
     */
    private $view;

    /**
     * @var array
     */
    private $parameters;

    /**
     * View constructor.
     * @param string $view
     * @param array $parameters
     *
     */
    public function __construct(string $view, array $parameters = [])
    {
        // Преобразуем параметр $view в путь до нужного шаблона
        $this->view = strtolower(str_replace('.','/',$view)) . ".html.twig";
        $this->parameters = $parameters;
    }

    /**
     * Метод проверяет существование шаблона $this->view и делает require при его наличии
     * Параметры для вывода находятся в массиве $parameters
     */
    public function render()
{
        $loader = new FilesystemLoader(VIEW_DIR . DIRECTORY_SEPARATOR);

        $twig = new Environment($loader, [
            'debug' => true,
            //'cache' => $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR .'tmp',
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addExtension(new TemplatesBlocksExtension());

        $twig->addGlobal('CurrentUrl', $_SERVER['REQUEST_URI']);

        echo $twig->render($this->view, $this->parameters);
    }

    /**
     * Возвращает параметры для вывода
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
