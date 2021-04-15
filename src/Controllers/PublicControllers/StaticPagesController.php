<?php


namespace App\Controllers\PublicControllers;

use App\Exception\HttpException;
use App\Redirect;
use App\StaticPages\Page;

class StaticPagesController extends PublicController
{
    /**
     * @param Page $page
     * @return array|void
     */
    public function index(Page $page): array
    {
        if($page->getParameter('isHidden') !== 0) {
            $content = strip_tags($page->getHtmlContent(), '<script>');
            $pageParameters = $page->getParameters();

            return ['view' => 'index', 'content' => $content, 'pageParameters' => $pageParameters];
        } else {
            return Redirect::to('/404');
        }
    }
}
