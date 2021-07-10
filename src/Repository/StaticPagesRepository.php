<?php

namespace App\Repository;

use App\Config;
use App\Exception\NotFoundException;
use App\StaticPages\FilesList;
use App\StaticPages\Page;
use App\StaticPages\PageList;
use Illuminate\Support\Collection;

/**
 * Class StaticPagesRepository
 * @package App\Repository
 */
class StaticPagesRepository extends Repository
{

    /**
     * @var PageList
     */
    private PageList $staticPages;

    /**
     * StaticPagesRepository constructor.
     * @throws NotFoundException
     */
    public function __construct()
    {
        parent::__construct();
        $this->getStaticPagesUrl();

    }

    /**
     * В staticPages записывает объект, тип которого указан в настройках статических страниц
     * тип "files" например указывает, что страницы хранятся в файловой системе
     * @throws NotFoundException
     */
    public function getStaticPagesUrl(): void
    {
        $config = Config::getInstance()->getConfig('cms');

        if (!empty($config['staticPages'])) {

            switch ($config['staticPages']) {
                case 'files':
                    $pages = new FilesList();
                    break;
                case 'db':
                    throw new NotFoundException('Компонент статических страниц пока не работает с БД', 510);
                default:
                    $pages = new FilesList();
            }

            $this->staticPages = (new PageList($pages));
        } else {
            throw new NotFoundException('В файле конфигурации CMS не указан тип данных статических страниц');
        }
    }

    /**
     * Возвращает коллекцию страниц
     * @return Collection
     */
    public function getStaticPagesCollection(): Collection
    {
        $pages = ($this->staticPages)->listPages();
        return new Collection($pages);
    }

    /**
     * возвращает объект PageList
     * @return PageList
     */
    public function getStaticPages(): PageList
    {
        return $this->staticPages;
    }

    /**
     * возвращает объект Page по имени файла
     * @param $filename
     * @return Page|null
     */
    public function getPageByFileName($filename): ?Page
    {
        return $this->staticPages->getPageByFileName($filename);
    }

}
