<?php


namespace App\Controllers\BackendControllers;

use App\Parse\Yaml;
use App\StaticPages\FilesList;
use App\StaticPages\PageList;
use App\Controllers\BackendControllers\AdminController;
use App\View;

class StaticPagesController extends AdminController
{
    /**
     * Метод выводит список статических страниц
     * @return View
     */
    public function index()
    {
        return new View('index', ['view' => 'admin.static_pages.list_pages_template', 'data' => [
            'title' => 'Контроллер статических страниц',
            'pages' => (new PageList(new FilesList()))->listPages()
        ]]);
    }

    /**
     * Форма редакктирования содержимого статическйо страницы
     * @return View
     */
    public function editPage()
    {
        return new View('admin', [
            'view' => 'admin.static_pages.create_page',
            'data' => [
                'form' => $this->getFields(),
                'token' => \Helpers\generateToken()
            ],
            'title' => 'Создание новой страницы'
        ]);
    }

    /**
     * Метод выводит массив генерируемых полей в форме
     * @return array
     */
    public function getFields()
    {
        return (new Yaml())->parseFile(__DIR__ . '/../../Model/StaticPage/page_fields.yaml');
    }

    /**
     * Удаление страницы
     * @return bool
     */
    public function deletePage(): bool
    {
        return false;
    }
}
