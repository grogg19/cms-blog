<?php

namespace App\StaticPages;

use App\Exception\HttpException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use App\StaticPages\File as File;

class FilesList implements PageListCompatible
{
    private string $filesDirectory =  DIRECTORY_SEPARATOR . 'static-pages';

    /**
     * FilesList constructor.
     * @throws HttpException
     */
    public function __construct()
    {
        if(!$this->checkOwnerDir()) {
            throw new HttpException('Не хватает прав на запись в каталог ' . $this->filesDirectory, 503);
        }
    }

    /**
     * Возвращает массив страниц класса Page
     * @return array
     */
    public function list(): array
    {

        $files = []; // Список файлов в директории
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(APP_DIR . $this->filesDirectory)) as $filename)
        {
            if ($filename->isDir()) continue;
            $files[$filename->getFileName()] = new File($filename->getRealPath());
        }
        return $files;
    }

    /**
     * @return bool
     */
    private function checkOwnerDir(): bool
    {
        if(!file_exists(APP_DIR . $this->filesDirectory)) {
            mkdir(APP_DIR . $this->filesDirectory, 0775);
        }
//        if(fileowner(APP_DIR . $this->filesDirectory) !== 33) {
//            return chown(APP_DIR . $this->filesDirectory, 'www-data');
//        }
        return true;
    }
}
