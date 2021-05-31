<?php


namespace App\Controllers\PublicControllers;

use App\Config;
use App\Exception\NotFoundException;
use App\Redirect;
use App\Request\Request;
use App\StaticPages\FilesList;
use App\StaticPages\Page;
use App\StaticPages\PageList;
use App\StaticPages\PageListCompatible;
use App\View;
use Illuminate\Support\Collection;
use function Helpers\cleanJSTags;

class StaticPagesController extends PublicController
{
    private PageListCompatible $staticPages;

    /**
     * StaticPagesController constructor.
     * @throws NotFoundException
     */
    public function __construct()
    {
        parent::__construct();
        $this->getStaticPagesUrl();
    }

    /**
     * @return View|null
     */
    public function index(): ?View
    {

        $url = (new Request())->server('REQUEST_URI');
        $page = (new PageList($this->staticPages))->getPageByUrl($url);

        if($page->getParameter('isHidden') !== 0 && $page instanceof Page) {
            $content = cleanJSTags($page->getHtmlContent());
            $pageParameters = $page->getParameters();

            return new View('index', ['view' => 'static_pages', 'data' => ['content' => $content, 'pageParameters' => $pageParameters]]);
        } else {
            Redirect::to('/404');
        }
        return null;
    }

    /**
     * @throws NotFoundException
     */
    public function getStaticPagesUrl(): void
    {
        $config = Config::getInstance()->getConfig('cms');

        if(!empty($config['staticPages'])) {

            $pages = match ($config['staticPages']) {
                'files' => new FilesList,
                'db' => throw new NotFoundException('компонент статических страниц пока не работает с БД', 510)
            };
            $this->staticPages = $pages;
        } else {
            throw new NotFoundException('В файле конфигурации CMS не указан тип данных статических страниц');
        }
    }

    /**
     * @return Collection
     */
    public function getStaticPages(): Collection
    {
        $pages = (new PageList($this->staticPages))->listPages();
        return new Collection($pages);
    }
}
