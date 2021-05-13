<?php
/**
 * Class Upload
 */

namespace App\Uploader;

use App\Config;
use App\Controllers\BackendControllers\AdminController;
use App\Cookie\Cookie;
use App\DI\DI;
use App\Model\Image;
use function Helpers\printArray;

class Upload extends AdminController
{
    public $filesToUpload;

    public function __construct(array $data)
    {
        $this->filesToUpload = $data;

        parent::__construct();
    }

    public function upload($configName = 'images')
    {
        $configImages = $this->getUploadSettings($configName);

        $files = $this->normalizeFilesArray($this->filesToUpload);

        // Проверяем каждое изображение, подходящее под mime-types из массива $configImages['mimeTypes'] и слайсим изображения
        foreach ($files as $file)
        {
            // Проверяем, нет ли ошибки при загрузке файлов
            if($file['error'] == 0) {

                // Проверяем загружаемые файлы на соответствие mime-типам и максимальному размеру для загрузки
                if(in_array(mime_content_type($file['tmp_name']), $configImages['mimeTypes']) && $file['size'] < ($configImages['maxImageSize'] * pow(1024,2)))
                {
                    // Если временный файл существует, то выгружаем его в папку UPLOAD_PATH
                    if(file_exists($file['tmp_name'])){
                        $fileName = time() . '_' . $file['name'];
                        $pathToFileHttp = SITE_ROOT . $configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $fileName;
                        if(move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $fileName)) {
                            $message['success'][] = 'Файл "'.$file["name"].'" успешно загружен.';
                            $message['uploadFilesData'][] = [
                                'path' => $pathToFileHttp,
                                'fileName' => $fileName
                            ];
                            $filesToCookie[] = $fileName;
                            $this->session->set('postBusy', true);

                        } else {
                            $message['error'][] = 'При загрузке файла "'.$file["name"].'" произошла ошибка.';
                        }
                    } else {
                        $message['error'][] = 'Временный файл "'.$file["name"].'" отсутствует, выберите загружаемые файлы заново.';
                    }
                } else {

                    $message['error'][] = 'Файл "'.$file["name"].'" не удовлетворяет требованиям к загрузке.';
                }
            } else {
                $message['error'][] = "Файл не загружен.";
            }
        }
        if(!empty($filesToCookie) && $configName = 'images') {
            if(!empty(Cookie::getArray('uploadImages'))) {
                $merge = array_merge(Cookie::getArray('uploadImages'), $filesToCookie);
                Cookie::setArray('uploadImages', $merge);
            } else {
                Cookie::setArray('uploadImages', $filesToCookie);
            }

        }
        return json_encode($message);
    }

    /**
     * метод получает настройки загрузки
     * @param $key
     * @return mixed|null
     */
    protected function getUploadSettings($key)
    {
        return Config::getInstance()->getConfig($key);
    }

    /**
     * метод приводит массив к структуре:
     * [files] => [
     *      [file1] => [
     *          name => value,
     *          tmp  => value,
     *          error => value
     *          ... => ...
     *      ],
     * ]
     * @param array $files
     * @return array
     */
    protected function normalizeFilesArray($files = []) {

        $normalized_array = [];

        foreach($files as $index => $file) {

            if (!is_array($file['name'])) {
                $normalized_array[] = $file;
                continue;
            }

            foreach($file['name'] as $idx => $name) {
                $normalized_array[$idx] = [
                    'name' => $name,
                    'type' => $file['type'][$idx],
                    'tmp_name' => $file['tmp_name'][$idx],
                    'error' => $file['error'][$idx],
                    'size' => $file['size'][$idx]
                ];
            }

        }

        return $normalized_array;
    }

}
