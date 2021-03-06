<?php

namespace App\Controllers\BackendControllers;

use App\FormRenderer;
use App\Pagination\PaginateMaker;
use App\Parse\Yaml;
use App\Redirect;
use App\Renderable;
use App\Repository\StaticPagesRepository;
use App\StaticPages\File;
use App\StaticPages\Page;
use App\Validate\Validator;
use App\View;
use App\Request\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class StaticPagesController
 * @package App\Controllers\BackendControllers
 */
class AdminStaticPagesController extends AdminController
{
    /**
     * @var array Правила валидации по дефолту
     */
    public array $rules = [
        'title' => 'required',
        'url'   => ['required', 'regex:/^\/[a-z0-9\/_\-\.]*$/i', 'uniquePage']
    ];

    /**
     * StaticPagesController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (!$this->auth->checkPermissons(['admin'])) {

            $this->toast->setToast('info', 'У вас недостаточно прав для этого действия');

            if (!empty(request()->server('HTTP_X_REQUESTED_WITH'))) {
                die(json_encode(['url' => '/']));
            } else {
                Redirect::to('/');
            }

        }

    }

    /**
     * Метод выводит список статических страниц
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $items = (new StaticPagesRepository())->getStaticPagesCollection();

        $title = 'Cтатические страницы';
        $page = !empty($request->get('page')) ? filter_var($request->get('page'), FILTER_SANITIZE_NUMBER_INT): 1;
        $quantity = !empty($request->get('quantity')) ? filter_var($request->get('quantity'), FILTER_SANITIZE_STRING) : 20;

        if ($quantity !== 'all') {
            $pages = (new PaginateMaker())->paginate($items, $quantity, $page);
        } else {
            $pages = Collection::make($items);
        }

        if ($pages instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $pages->setPath('static-pages' . $query);
        }

        $dataListStaticPages = [
            'token' => generateToken(),
            'title' => $title,
            'pages' => $pages,
            'quantity' => $quantity,
        ];

        if ($quantity !== 'all') {
            $dataListStaticPages['paginator'] = $pages;
        }

        return new View('admin.static_pages.list_pages_template', $dataListStaticPages);
    }

    /**
     * Рендер формы создания страницы
     * @return Renderable
     */
    public function createPage(): Renderable
    {
        $form = $this->getFields();

        $formFields = (new FormRenderer($form['fields']))->render();

        $dataPage = [
            'form' => $form,
            'token' => generateToken(),
            'title' => 'Создание новой страницы',
            'formFields' => $formFields
        ];

        return new View('admin.static_pages.create_page', $dataPage);
    }

    /**
     * Форма редактирования содержимого статической страницы
     * @param Request $request
     * @return Renderable
     */
    public function editPage(Request $request): Renderable
    {

        if (empty($request->post('pageName')) && !checkToken()) {
            Redirect::to('/admin/static-pages');
        }

        $page = (new StaticPagesRepository())->getPageByFileName( (string) $request->post('pageName'));

        $form = $this->getFields();
        $pageParameters = (object) $page->getParameters();

        $formFields = (new FormRenderer($form['fields']))->render($pageParameters);



        $dataPage = [
            'form' => $form,
            'token' => generateToken(),
            'title' => 'Редактирование страницы',
            'formFields' => $formFields,
            'pageName' => $request->post('pageName')
        ];

        return new View('admin.static_pages.edit_page', $dataPage);
    }

    /**
     * Сохранение данных страницы
     * @param Request $request
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function savePage(Request $request): string
    {
        if (!checkToken()) {
            $this->toast->getToast('info', 'Сессия закончилась, обновите пожалуйста страницу!');
            exit();
        }
        if (!empty($request->post('edit_form'))) {

            $pages = (new StaticPagesRepository())->getStaticPages();
            $existPage = $pages->getPageByFileName($request->post('pageName'));

            if ($existPage !== null) {
                $existPage->deletePage();
            } else {
                return $this->toast->getToast('warning', 'Такой страницы не существует!');
            }

        }

        $validation = new Validator($request->post(), '', $this->rules);
        $resultValidation = $validation->makeValidation();

        if (!empty($resultValidation)) {
            return json_encode($resultValidation);
        }

        $page = new Page;
        $page->setParameters([
            'title' => filter_var($request->post('title'), FILTER_SANITIZE_STRING),
            'url' => filter_var($request->post('url'), FILTER_SANITIZE_STRING),
            'isHidden' => !empty($request->post('isHidden')) && $request->post('isHidden') == 'on' ? 1 : 0,
            'navigationHidden' => !empty($request->post('navigationHidden')) && $request->post('navigationHidden') == 'on' ? 1 : 0,
        ]);
        $page->setHtmlContent(cleanJSTags((string) $request->post('content')));
        $page->makePage(new File);

        $this->toast->setToast('success', 'Данные страницы успешно сохранены.');

        return json_encode(['url' => '/admin/static-pages']);
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
     * @param Request $request
     * @return string
     */
    public function deletePage(Request $request): string
    {
        if (!checkToken() || empty($request->post('pageName'))) {
            return $this->toast->getToast('warning', 'Не хватает данных для удаления попробуйте еще раз!');
        }

        $page = (new StaticPagesRepository())->getPageByFileName( (string) $request->post('pageName'));

        if ($page->deletePage()) {

            $this->toast->setToast('success', 'Страница успешно удалена.');

            return json_encode(['url' => '/admin/static-pages']);
        }
        return $this->toast->getToast('warning', 'Невозможно удалить страницу!');
    }
}
