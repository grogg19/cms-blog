<?php


namespace App\Controllers\BackendControllers;

use App\Parse\Yaml;
use App\StaticPages\FilesList;
use App\StaticPages\File;
use App\StaticPages\Page;
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
        'slug'   => ['required', 'regex:/^\/[a-z0-9\/_\-\.]*$/i', 'uniquePage']
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

    /**
     * @return array|string
     * @throws \App\Exception\ValidationException
     */
    public function savePage(): array|string
    {
        if(checkToken()) {
            $validation = new Validator($this->request->post(), '', $this->rules);
            $resultValidation = $validation->makeValidation();
            if(empty($resultValidation)) {

                $page = new Page;
                $page->setParameters([
                    'title' => $this->request->post('title'),
                    'url' => $this->request->post('slug'),
                    'isHidden' => !empty($this->request->post('isHidden')) && $this->request->post('isHidden') == 'on' ? 1 : 0,
                    'navigationHidden' => !empty($this->request->post('navigationHidden')) && $this->request->post('navigationHidden') == 'on' ? 1 : 0,
                ]);
                $page->setHtmlContent((string) $this->request->post('content'));
                $page->makePage(new File);
                return json_encode([
                    'url' => '/admin/static-pages'
                ]);
            } else {
                return json_encode($resultValidation);
            }
        } else {
            return json_encode([
                'error' => [
                    'title' => [
                        'field' => 'title',
                        'errorMessage' => 'Session is old, update form!'
                    ]
                ]
            ]);
        }

    }

    /**
     * Метод выводит массив генерируемых полей в форме
     * @return array
     */
    public function getFields(): array
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
