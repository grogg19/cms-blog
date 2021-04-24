<?php
/**
 * Class isUnique
 */
namespace App\Validate\Validation;

use App\StaticPages\FilesList;
use App\StaticPages\Page;
use App\StaticPages\PageList;
use App\StaticPages\PageListCompatible;
use App\Validate\Validation;
use App\Config;

class IsUniquePage extends Validation
{

    /**
     * isUnique constructor.
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->isUniquePage();
    }

    /**
     * @return bool
     */
    private function isUniquePage(): bool
    {
        if(Config::getInstance()->getConfig('db')['staticPages'] === 'files') {

            $pages = new PageList(new FilesList());

            if($pages->getPageByUrl($this->data) !== null) {
                $this->message = 'Страница с таким URL уже существует';
                return false;
            }
            return true;
        }
        return false;
    }
}
