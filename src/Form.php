<?php
/**
 * Class Form
 */

namespace App;

/**
 * Class Form
 * @package App
 */
class Form implements Renderable
{
    /**
     * @var View
     */
    protected $view;

    /**
     * Form constructor.
     * @param string $viewFile
     * @param array $parameters
     */
    public function __construct(string $viewFile, array $parameters = [])
    {
        $this->view = new View($viewFile, $parameters);
    }

    /**
     * Возвращает View формы
     */
    public function render(): void
    {
        $this->view->render();
    }

}
