<?php


namespace App\Controllers\BackendControllers;

use App\Parse\Yaml;
use App\StaticPages\FilesList;
use App\StaticPages\PageList;
use App\Controllers\BackendControllers\AdminController as AdminController;
use App\Validate\Validator;
use App\View;

use function Helpers\checkToken;
use function Helpers\generateToken;

class StaticPagesController extends AdminController
{
    /**
     * @var array The rules to be applied to the data.
     */
    public array $rules = [
        'title' => 'required',
        'url'   => ['required', 'regex:/^\/[a-z0-9\/_\-\.]*$/i', 'uniquePage']
    ];

    /**
     * Метод выводит список статических страниц
     * @return View
     */
    public function index()
    {
        $title = 'Контроллер статических страниц';

        return new View('admin', [
            'view' => 'admin.static_pages.list_pages_template',
                'data' => [
                'title' => $title,
                'pages' => (new PageList(new FilesList()))->listPages()
                ],
            'title' => $title
        ]);
    }

    /**
     * Форма редакктирования содержимого статической страницы
     * @return View
     */
    public function editPage()
    {
        return new View('admin', [
            'view' => 'admin.static_pages.create_page',
            'data' => [
                'form' => $this->getFields(),
                'token' => generateToken()
            ],
            'title' => 'Создание новой страницы'
        ]);
    }

    public function savePage()
    {
        if(checkToken()) {
            $validation = new Validator($this->request->post(), $this->rules);
            $validation->makeValidation();
            //$validateResult = ((new Validation())->validate($this->request->post(), $this->rules));
        }


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
