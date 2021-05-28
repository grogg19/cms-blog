<?php


namespace App\Controllers\BackendControllers;

use App\Controllers\ToastsController;
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

class StaticPagesController extends AdminController
{
    /**
     * @var array The rules to be applied to the data.
     */
    public array $rules = [
        'title' => 'required',
        'url'   => ['required', 'regex:/^\/[a-z0-9\/_\-\.]*$/i', 'uniquePage']
    ];

    public function __construct()
    {
        parent::__construct();

        $this->auth->checkPermissons(['admin', 'content-manager']);

    }

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

    /**
     * @return View
     */
    public function createPage(): View
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
    public function editPage(): View
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
                    return ToastsController::getToast('warning', 'Такой страницы не существует!');
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

                (new ToastsController())->setToast('success', 'Данные страницы успешно сохранены.');

                return json_encode([
                    'url' => '/admin/static-pages'
                ]);
            } else {
                return json_encode($resultValidation);
            }
        } else {
            return ToastsController::getToast('warning', 'Сессия устарела, обновите страницу!');
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

                (new ToastsController())->setToast('success', 'Страница успешно удалена.');

                return json_encode([
                    'url' => '/admin/static-pages'
                ]);
            }
        }
        return ToastsController::getToast('warning', 'Невозможно удалить страницу!');
    }
}
