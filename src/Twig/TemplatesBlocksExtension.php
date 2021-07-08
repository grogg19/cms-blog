<?php


namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Controllers\PublicControllers\PublicPostController;

/**
 * Class PostExtension
 * @package App\Twig
 */
class TemplatesBlocksExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('latestPosts', [$this, 'callLatestPostsController']),
        ];
    }

    public function callLatestPostsController(string $view = '')
    {
        $latestPosts = (new PublicPostController())->latestPosts($view);
        $latestPosts->render();
    }
}
