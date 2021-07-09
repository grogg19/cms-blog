<?php


namespace App\Twig;

use App\Config;
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
            new TwigFunction('latestPosts', [$this, 'renderLatestPosts']),
            new TwigFunction('quantityItems', [$this, 'quantityFormItems']),
        ];
    }

    /**
     * Отрисовка списка свежих постов
     * @param string $view
     */
    public function renderLatestPosts(string $view = '')
    {
        $latestPosts = (new PublicPostController())->latestPosts($view);
        $latestPosts->render();
    }

    /**
     * список вариантов количества элементов на странице
     * @return array
     */
    public function quantityFormItems(): array
    {
        return Config::getInstance()->getConfig('cms')['dropdown'];
    }
}
