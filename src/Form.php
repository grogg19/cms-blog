<?php
/**
 * Class Form
 */

namespace App;

use App\View;

/**
 * Class Form
 * @package App
 */
class Form
{
    /**
     * @var \App\View
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
     * @return mixed|void
     */
    public function render()
    {
        return $this->view->render();
    }

}
