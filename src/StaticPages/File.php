<?php

namespace App\StaticPages;

use SplFileObject;
use Exception;
use App\StaticPages\PageCompatible as PageCompatible;

/**
 * Class File
 * @package App\StaticPages
 */
class File implements PageCompatible
{
    public SplFileObject $file;
    public array $parameters;
    private string $staticPagesDirectory = DIRECTORY_SEPARATOR . 'static-pages';


    /**
     * File constructor.
     * @param string $pathToFile
     */
    public function __construct(string $pathToFile= '')
    {
        if(!empty($pathToFile) && (file_exists($pathToFile))) {
            $this->file = new SplFileObject($pathToFile, 'r+');
            $this->getContent();
        }
    }

    /**
     * Метод создает и возвращает новый файл
     * @param string $url
     * @throws Exception
     */
    public function create(string $url): void
    {
        if($this->checkExistUrl($url)) {
            throw new Exception('Такой url уже существует', 405);
        }

        $pattern = '/^[a-z0-9_\-\.\/]+$/i';

        if($url[0] === '/' && preg_match($pattern, $url, $fileName)) {

            $fileName = substr($url, 1, strlen($url));
            $fileName = str_replace(['/', '.', ' '], ['-', '', ''], $fileName) ;

            $pathToFile = APP_DIR . $this->staticPagesDirectory . DIRECTORY_SEPARATOR . $fileName;
            $increment = 0;
            while(file_exists($pathToFile . '.htm')) {
                $pathToFile = $pathToFile . '-' . $increment++;
            }

            $this->file = new SplFileObject($pathToFile . '.htm', 'w+');
            chmod($this->file->getRealPath(), 0777);

        } else {
            throw new Exception('Плохой URI', 401);
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData(): array
    {
        if(empty($this->file) && empty($this->parameters)) {
            throw new Exception('Объект не найден', 400);
        } else {
            return $this->parameters;
        }
    }

    /**
     * Сохраняет данные в файл
     * @param array $data
     * @return bool
     */
    public function saveData(array $data): bool
    {
        $html = $data['htmlContent'];
        unset($data['htmlContent']);

        $contentFile = '[pageParameters]' . PHP_EOL;
        $contentFile .= $this->arrayToIni($data);
        $contentFile .= '====' .PHP_EOL;
        $contentFile .= $html;

        $this->file->ftruncate(0);
        $this->file->fseek(0);
        return (bool) $this->file->fwrite($contentFile);
    }

    /**
     * Метод удаляет файл
     * @return bool
     */
    public function delete(): bool
    {
        if(file_exists($this->file->getRealPath())) {
            unlink($this->file->getRealPath());
            return true;
        }
        return false;
    }


    /**
     * Получаем параметры и контент страницы из файла
     */
    private function getContent(): void
    {
        list($parameters, $htmlContent) = explode('====', $this->file->fread($this->file->getSize()));
        $this->parameters = parse_ini_string($parameters);
        $this->parameters['htmlContent'] = $htmlContent;
    }

    /**
     * Возвращает конвертированную строку из массива в формате для записи INI файла
     * @param array $dataArray
     * @param array $parentArray
     * @return string
     */
    private function arrayToIni(array $dataArray, array $parentArray = array())
    {
        $out = '';
        foreach ($dataArray as $key => $value)
        {
            if (is_array($value))
            {
                //subsection case
                //merge all the sections into one array...
                $section = array_merge((array) $parentArray, (array) $key);
                //add section information to the output
                $out .= '[' . join('.', $section) . ']' . PHP_EOL;
                //recursively traverse deeper
                $out .= $this->arrayToIni($value, $section);
            }
            else
            {
                //plain key->value case
                $out .= "$key = $value" . PHP_EOL;
            }
        }
        return $out;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function checkExistUrl(string $url): bool
    {
        $filesList = new FilesList();
        foreach ($filesList->list() as $item) {
            if($item->parameters['url'] === $url) {
                return true;
            }
        }
        return false;
    }

}
