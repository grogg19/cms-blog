<?php


namespace App\Controllers\PublicControllers;

use App\Redirect;
use App\Renderable;
use App\Repository\StaticPagesRepository;
use App\StaticPages\Page;
use App\StaticPages\PageList;
use App\View;

class StaticPagesController extends PublicController
{

    /**
     * @var PageList
     */
    private PageList $staticPages;

    /**
     * StaticPagesController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->staticPages = (new StaticPagesRepository())->getStaticPages();
    }

    /**
     * метод рендерит статические страницы
     * @return Renderable|null
     */
    public function index(): ?Renderable
    {
        $url = $this->request->server('REQUEST_URI');

        $page = $this->staticPages->getPageByUrl($url);

        if ($page->getParameter('isHidden') != 0 && $page instanceof Page) {
            $content = cleanJSTags($page->getHtmlContent());
            $pageParameters = $page->getParameters();

            $pageData = [
                'content' => $content,
                'pageParameters' => $pageParameters,
                'title' => 'Блог | ' . $pageParameters['title'],
            ];

            $view = 'static_pages';

            return new View($view, $pageData);

        } else {
            Redirect::to('/404');
        }
        return null;
    }

}
