<?php


namespace App\Controllers\PublicControllers;

use App\Redirect;
use App\Renderable;
use App\Repository\StaticPagesRepository;
use App\Repository\UserRepository;
use App\Request\Request;
use App\StaticPages\Page;
use App\StaticPages\PageList;
use App\View;

use function Helpers\cleanJSTags;

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
        $url = (new Request())->server('REQUEST_URI');

        $page = $this->staticPages->getPageByUrl($url);

        if($page->getParameter('isHidden') != 0 && $page instanceof Page) {
            $content = cleanJSTags($page->getHtmlContent());
            $pageParameters = $page->getParameters();

            $data = [
                'content' => $content,
                'pageParameters' => $pageParameters,
                'title' => 'Блог | ' . $pageParameters['title'],
            ];

            $data['user'] = (session_status() === 2) ? (new UserRepository())->getCurrentUser() : null;

            return new View('static_pages', $data);

        } else {
            Redirect::to('/404');
        }
        return null;
    }

}
