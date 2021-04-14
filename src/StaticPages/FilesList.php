<?php

namespace App\StaticPages;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use App\StaticPages\File as File;

class FilesList implements PageListCompatible
{
    private string $filesDirectory =  DIRECTORY_SEPARATOR . 'static-pages';

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
}
