<?php


namespace App\Controllers\BackendControllers;

use App\Parse\Yaml;
use App\Redirect;
use App\StaticPages\FilesList;
use App\StaticPages\File;
use App\StaticPages\Page;
use App\StaticPages\PageList;
use App\Controllers\BackendControllers\AdminController as AdminController;
use App\Validate\Validator;
use App\View;

use function Helpers\checkToken;
use function Helpers\cleanJSTags;
use function Helpers\generateToken;
use function Helpers\parseRequestUri;

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
    public function index(): View
    {
        $title = 'Контроллер статических страниц';

        return new View('admin', [
            'view' => 'admin.static_pages.list_pages_template',
                'data' => [
                    'token' => generateToken(),
                    'title' => $title,
                    'pages' => (new PageList(new FilesList()))->listPages()
                ],
            'title' => $title
        ]);
    }

    public function createPage()
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
     * Форма редактирования содержимого статической страницы
     * @return View
     */
    public function editPage()
    {

        if(empty($this->request->post('pageName')) && !checkToken()) {
            Redirect::to('/admin/static-pages');
        }

        $page = (new PageList(new FilesList()))->getPageByFileName( (string) $this->request->post('pageName'));

        return new View('admin', [
            'view' => 'admin.static_pages.edit_page',
            'data' => [
                'form' => $this->getFields(),
                'page' => (object) $page->getParameters(),
                'token' => generateToken()
            ],
            'title' => 'Редактирование страницы'
        ]);
    }

    /**
     * @return array|string
     * @throws \App\Exception\ValidationException
     */
    public function savePage(): array|string
    {
        if(checkToken()) {

            if(!empty($this->request->post('edit_form'))) {

                $pages = new PageList(new FilesList());
                $existPage = $pages->getPageByUrl(filter_var($this->request->post('url'), FILTER_SANITIZE_STRING));

                if($existPage !== null) {
                    $existPage->deletePage();
                } else {
                    return json_encode([
                        'error' => [
                            'title' => [
                                'field' => 'url',
                                'errorMessage' => 'Такой страницы не существует!'
                            ]
                        ]
                    ]);
                }

            }

            $validation = new Validator($this->request->post(), '', $this->rules);
            $resultValidation = $validation->makeValidation();
            if(empty($resultValidation)) {

                $page = new Page;
                $page->setParameters([
                    'title' => filter_var($this->request->post('title'), FILTER_SANITIZE_STRING),
                    'url' => filter_var($this->request->post('url'), FILTER_SANITIZE_STRING),
                    'isHidden' => !empty($this->request->post('isHidden')) && $this->request->post('isHidden') == 'on' ? 1 : 0,
                    'navigationHidden' => !empty($this->request->post('navigationHidden')) && $this->request->post('navigationHidden') == 'on' ? 1 : 0,
                ]);
                $page->setHtmlContent(cleanJSTags((string) $this->request->post('content')));
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
     * @return string
     */
    public function deletePage(): string
    {
        if(checkToken() && !empty($this->request->post('pageName'))) {
            $page = (new PageList(new FilesList()))->getPageByFileName( (string) $this->request->post('pageName'));
            if($page->deletePage()) {
                return json_encode([
                    'url' => '/admin/static-pages'
                ]);
            }
        }
        return json_encode([
            'error' => [
                'title' => [
                    'field' => 'messageWindow',
                    'errorMessage' => 'Невозможно удалить страницу!'
                ]
            ]
        ]);
    }
}
