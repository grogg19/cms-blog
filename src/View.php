<?php
/**
 * Класс View
 */

namespace App;

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
        $this->view = VIEW_DIR . strtolower(str_replace('.','/',$view)) . ".php";
        $this->parameters = $parameters;
    }

    /**
     * Метод проверяет существование шаблона $this->view и делает require при его наличии
     * Параметры для вывода находятся в массиве $parameters
     */
    public function render()
    {
        extract($this->parameters);

        if(file_exists($this->view)) {
            ob_start();
            require $this->view;
            $out = ob_get_contents();
            ob_end_clean();
            echo $out;
        } else {
            echo "Данного шаблона не существует";
        }
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
