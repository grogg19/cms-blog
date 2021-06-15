<?php
/**
 * Class Upload
 */

namespace App\Uploader;

use App\Config;
use App\Controllers\BackendControllers\AdminController;
use App\Cookie\Cookie;

/**
 * Class Upload
 * @package App\Uploader
 */
class Upload extends AdminController
{
    /**
     * @var array
     */
    public $filesToUpload;

    /**
     * Upload constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->filesToUpload = $data;

        parent::__construct();
    }

    /**
     * @param string $configName
     * @return false|string
     */
    public function upload(string $configName = 'images')
    {
        $configImages = $this->getUploadSettings($configName);

        $files = $this->normalizeFilesArray($this->filesToUpload);

        // Проверяем каждое изображение, подходящее под mime-types из массива $configImages['mimeTypes'] и слайсим изображения
        foreach ($files as $file)
        {
            // Проверяем, нет ли ошибки при загрузке файлов
            if ($file['error'] !== 0) {
                $message['error'][] = "Файл не загружен.";
            }

            $fileMimeType = mime_content_type($file['tmp_name']);
            $maxImageSize = $configImages['maxImageSize'] * pow(1024,2);

            // Проверяем загружаемые файлы на соответствие mime-типам и максимальному размеру для загрузки
            if(!in_array($fileMimeType, $configImages['mimeTypes']) || $file['size'] < ($maxImageSize)) {
                //return ToastsController::getToast('warning', 'Файл "'.$file["name"].'" не удовлетворяет требованиям к загрузке.');
                $message['error'][] = 'Файл "'.$file["name"].'" не удовлетворяет требованиям к загрузке.';
            }


            if(!file_exists($file['tmp_name'])) {
                $message['error'][] = 'Временный файл "'.$file["name"].'" отсутствует, выберите загружаемые файлы заново.';
            }

            // Если временный файл существует, то выгружаем его в папку UPLOAD_PATH

            $fileName = time() . '_' . $file['name'];
            $pathToFileHttp = SITE_ROOT . $configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $fileName;

            if(!move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $fileName)) {
                $message['error'][] = 'При загрузке файла "'.$file["name"].'" произошла ошибка.';
            }

            $message['success'][] = 'Файл "'.$file["name"].'" успешно загружен.';
            $message['uploadFilesData'][] = [
                'path' => $pathToFileHttp,
                'fileName' => $fileName
            ];

            $filesToCookie[] = $fileName;
            $this->session->set('postBusy', true);

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
     * @return array
     */
    protected function getUploadSettings($key): array
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
    protected function normalizeFilesArray(array $files = []): array
    {
        $normalized_array = [];

        foreach($files as $file) {

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
