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
    private $data;

    /**
     * @var array
     */
    private $mergeData;

    /**
     * View constructor.
     * @param string $view
     * @param array $data
     *
     */
    public function __construct(string $view, array $data = [], array $mergeData = [])
    {
        // Преобразуем параметр $view в путь до нужного шаблона
        $this->view = VIEW_DIR . strtolower(str_replace('.','/',$view)) . ".php";
        $this->data = $data;
        $this->mergeData = $mergeData;
    }

    /**
     * Метод проверяет существование шаблона $this->view и делает require при его наличии
     * Параметры для вывода находятся в массиве $parameters
     */
    public function render()
    {
        $template = !isset($this->data['ajax']) ? 'main_template' : '';

        return $this->renderLayout($template, $this->renderView($this->view, $this->data), $this->mergeData);
    }

    /**
     * отрисовывает View
     * @param $view
     * @param $data
     * @return false|string
     */
    private function renderView($view, $data) {

        if (file_exists($view)) {
            ob_start();

            extract($data);    // массив в переменные
            include $view; // подключаем файл с представлением
            $out = ob_get_contents();
            ob_get_clean();
            return $out;
        }
        return "Данного шаблона не существует";
    }

    /** Отрисовывает основной шаблон
     * @param string $template
     * @param string $content
     * @param array $mergeData
     * @return false|mixed|string
     */
    private function renderLayout(string $template = '', string $content = '', array $mergeData = []) {

        $layoutPath = VIEW_DIR . $template . '.php';

        if (file_exists($layoutPath)) {
            ob_start();

            extract($mergeData);

            include $layoutPath; // тут будут доступны переменные $title и $content
            $out = ob_get_contents();
            ob_get_clean();
            return $out;
        }
        if(empty($template)) {
            return $content;
        }
    }

    /**
     * Возвращает параметры для вывода
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

//    public function with($key, $value = null)
//    {
//        if (is_array($key)) {
//            $this->data = array_merge($this->data, $key);
//        } else {
//            $this->data[$key] = $value;
//        }
//
//        return $this;
//    }
}
