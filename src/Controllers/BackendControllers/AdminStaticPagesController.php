<?php

namespace App\Controllers\BackendControllers;

use App\Pagination\PaginateMaker;
use App\Toasts\Toast;
use App\Parse\Yaml;
use App\Redirect;
use App\Renderable;
use App\Repository\StaticPagesRepository;
use App\StaticPages\File;
use App\StaticPages\Page;
use App\Validate\Validator;
use App\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use function Helpers\checkToken;
use function Helpers\cleanJSTags;
use function Helpers\generateToken;

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

        $this->auth->checkPermissons(['admin', 'content-manager']);
    }

    /**
     * Метод выводит список статических страниц
     * @return Renderable
     */
    public function index(): Renderable
    {
        $items = (new StaticPagesRepository())->getStaticPagesCollection();

        $title = 'Cтатические страницы';
        $page = (!empty($this->request->get('page'))) ? filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT): 1;
        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;

        if($quantity !== 'all') {
            $pages = (new PaginateMaker())->paginate($items, $quantity, $page);
        } else {
            $pages = Collection::make($items);
        }

        if($pages instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $pages->setPath('static-pages' . $query);
        }

        $data = [
            'view' => 'admin.static_pages.list_pages_template',
                'data' => [
                    'token' => generateToken(),
                    'title' => $title,
                    'pages' => $pages,
                    'quantity' => $quantity
                ],
            'title' => $title
        ];

        return (new View('admin', $data));
    }

    /**
     * Рендер формы создания страницы
     * @return Renderable
     */
    public function createPage(): Renderable
    {
        $title = 'Создание новой страницы';

        $data = [
            'view' => 'admin.static_pages.create_page',
            'data' => [
                'form' => $this->getFields(),
                'token' => generateToken(),
                'title' => $title
            ],
            'title' => $title
        ];

        return (new View('admin', $data));
    }

    /**
     * Форма редактирования содержимого статической страницы
     * @return Renderable
     */
    public function editPage(): Renderable
    {

        if(empty($this->request->post('pageName')) && !checkToken()) {
            Redirect::to('/admin/static-pages');
        }

        $page = (new StaticPagesRepository())->getPageByFileName( (string) $this->request->post('pageName'));

        $title = 'Редактирование страницы';

        $data = [
            'view' => 'admin.static_pages.edit_page',
            'data' => [
                'form' => $this->getFields(),
                'page' => (object) $page->getParameters(),
                'token' => generateToken(),
                'title' => $title
            ],
            'title' => $title
        ];

        return (new View('admin', $data));
    }

    /**
     * Сохранение данных страницы
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function savePage(): string
    {
        if(!checkToken()) {
            return (new Toast())->getToast('warning', 'Сессия устарела, обновите страницу!');
        }

        if(!empty($this->request->post('edit_form'))) {

            $pages = (new StaticPagesRepository())->getStaticPages();
            $existPage = $pages->getPageByUrl(filter_var($this->request->post('url'), FILTER_SANITIZE_STRING));

            if($existPage !== null) {
                $existPage->deletePage();
            } else {
                return (new Toast())->getToast('warning', 'Такой страницы не существует!');
            }

        }

        $validation = new Validator($this->request->post(), '', $this->rules);
        $resultValidation = $validation->makeValidation();

        if(!empty($resultValidation)) {
            return json_encode($resultValidation);
        }

        $page = new Page;
        $page->setParameters([
            'title' => filter_var($this->request->post('title'), FILTER_SANITIZE_STRING),
            'url' => filter_var($this->request->post('url'), FILTER_SANITIZE_STRING),
            'isHidden' => !empty($this->request->post('isHidden')) && $this->request->post('isHidden') == 'on' ? 1 : 0,
            'navigationHidden' => !empty($this->request->post('navigationHidden')) && $this->request->post('navigationHidden') == 'on' ? 1 : 0,
        ]);
        $page->setHtmlContent(cleanJSTags((string) $this->request->post('content')));
        $page->makePage(new File);

        (new Toast())->setToast('success', 'Данные страницы успешно сохранены.');

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
     * @return string
     */
    public function deletePage(): string
    {
        if(!checkToken() || empty($this->request->post('pageName'))) {
            return (new Toast())->getToast('warning', 'Не хватает данных для удаления попробуйте еще раз!');
        }

        $page = (new StaticPagesRepository())->getPageByFileName( (string) $this->request->post('pageName'));

        if($page->deletePage()) {

            (new Toast())->setToast('success', 'Страница успешно удалена.');

            return json_encode(['url' => '/admin/static-pages']);
        }
        return (new Toast())->getToast('warning', 'Невозможно удалить страницу!');
    }
}
