<?php


namespace App\StaticPages;

use App\StaticPages\Page as Page;
use App\StaticPages\PageListCompatible as PageListCompatible;

/**
 * Class PageList
 * @package App\StaticPages
 */
class PageList
{
    /**
     * @var \App\StaticPages\PageListCompatible
     */
    private PageListCompatible $pageList;

    /**
     * PageList constructor.
     * @param \App\StaticPages\PageListCompatible $pageList
     */
    public function __construct(PageListCompatible $pageList)
    {
        $this->pageList = $pageList;
    }

    /**
     * Возвращает массив объектов Page
     * @return array
     */
    public function listPages(): array
    {
        $pages = [];
        foreach ($this->pageList->list() as $item) {
            $pages[$item->parameters['url']] = new Page($item);
        }
        return $pages;
    }

    /**
     * Метод возвращает страницу по её URI
     * @param string $url
     * @return \App\StaticPages\Page|null
     */
    public function getPageByUrl(string $url): Page|null
    {
        foreach ($this->pageList->list() as $item) {

           if($item->parameters['url'] === $url) {
               return new Page($item);
           }

        }
        return null;
    }
}
