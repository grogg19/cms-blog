<?php


namespace App\Controllers\PublicControllers;

use App\Config;
use App\Exception\NotFoundException;
use App\Request\Request;
use App\StaticPages\FilesList;
use App\StaticPages\Page;
use App\StaticPages\PageList;
use App\StaticPages\PageListCompatible;

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
     * @param Page $page
     * @return array|void
     */
    public function index()
    {

        $url = (new Request())->server('REQUEST_URI');

        $page = (new PageList($this->staticPages))->getPageByUrl($url) instanceof Page ?? null;
        dd($page);
        //dump((new Request())->server('REQUEST_URI'));
//        if($page->getParameter('isHidden') !== 0) {
//            $content = strip_tags($page->getHtmlContent(), '<script>');
//            $pageParameters = $page->getParameters();
//
//            return ['view' => 'index', 'content' => $content, 'pageParameters' => $pageParameters];
//        } else {
//            return Redirect::to('/404');
//        }
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

    public function getStaticPages()
    {
        return (new PageList($this->staticPages))->listPages();
    }
}
