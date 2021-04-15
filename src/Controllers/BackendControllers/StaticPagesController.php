<?php


namespace App\Controllers\BackendControllers;

use App\StaticPages\FilesList;
use App\StaticPages\PageList;
use App\Controllers\BackendControllers\AdminController;
use App\View;

class StaticPagesController extends AdminController
{
    public function index()
    {
        return new View('index', ['view' => 'admin.static_pages.list_pages_template', 'data' => [
            'title' => 'Контроллер статических страниц',
            'pages' => (new PageList(new FilesList()))->listPages()
        ]]);
    }
}
