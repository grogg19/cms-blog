<?php
/**
 * Class PublicController
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Jsonable;
use App\Renderable;
use App\View;

class PublicController extends Controller implements Renderable, Jsonable
{
    /**
     * @var string;
     */
    protected $view = 'index';

    /**
     * @var array
     */
    protected $data;

    /**
     * Отрисовка контента
     */
    public function render(): void
    {
        (new View($this->view, $this->data))->render();
    }

    /**
     * Возвращает Json
     * @return string
     */
    public function json(): string
    {
        return json_encode($this->data);
    }
}
