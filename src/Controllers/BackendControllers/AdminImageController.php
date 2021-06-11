<?php
/**
 * Класс AdminImageController
 */

namespace App\Controllers\BackendControllers;

use App\Model\Image;
use App\Config;
use App\Cookie\Cookie;

use function Helpers\checkToken;
use function Helpers\request;

/**
 * Class AdminImageController
 * @package App\Controllers\BackendControllers
 */
class AdminImageController
{
    /**
     * @var array
     */
    private $configImages;

    /**
     * AdminImageController constructor.
     */
    public function __construct()
    {
        $this->configImages = Config::getInstance()->getConfig('images');
    }

    /**
     * метод удаляет файлы ищображений
     * @param array $fileNames
     * @return string
     */
    public function imageDestructor(array $fileNames = []): string
    {

        if(!empty(Cookie::getArray('uploadImages'))) {
            $listFilesForDelete = array_diff($fileNames, $this->imageCheckAvailabilityInDb($fileNames));
        } else {
            $listFilesForDelete = $fileNames;
        }

        $message = []; // сообщения об ошибках

        foreach ($listFilesForDelete as $fileName) {

            $this->deleteImageFromDb($fileName);

            $pathToFile = $_SERVER['DOCUMENT_ROOT'] . $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $fileName;

            if (file_exists($pathToFile)) {

                unlink($pathToFile);
                $message['successMsg'][] = 'Файл ' . $fileName . ' успешно удалён.';
            } else {
                $message['errorMsg'][] = 'Такого файла не существует';
            }
        }
        return json_encode($message);
    }

    /**
     * @param array $fileNames
     * @return array
     */
    public function imageCheckAvailabilityInDb(array $fileNames = [])
    {
        foreach (Image::whereIn('file_name', $fileNames)->get() as $item) {
            $listImages[] = $item->file_name;
        }
        return (isset($listImages)) ? $listImages : [];
    }

    /**
     * @param $fileName
     * @return mixed
     */
    public function deleteImageFromDb($fileName)
    {
        return Image::where('file_name', $fileName)->delete();
    }


    /**
     * Метод чистит картинки из массива в куки
     */
    public function cacheImageClean() {

        if(!empty(Cookie::getArray('uploadImages'))) {

            $this->imageDestructor(Cookie::getArray('uploadImages'));

            Cookie::delete('uploadImages');
        }
    }

    /**
     * Возвращает массив изображений из хранилища
     * @return string
     */
    public function getImageNameFromStorages(): string
    {
        if(!checkToken()) {
            return json_encode(['error' => 'Ошибка доступа']);
        }

        if(!empty(request()->post('postId'))) {
            $result = Image::where('post_id', request()->post('postId'))->get('file_name');

            foreach ($result as $imageFileName) {
                $imagesPath[] = [
                    'path' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName['file_name'],
                    'fileName' => $imageFileName['file_name'],
                    'fileSize' => filesize($_SERVER['DOCUMENT_ROOT'] . $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName['file_name'])
                ];
            }
        }

        if(!empty(Cookie::getArray('uploadImages'))) {

            foreach (Cookie::getArray('uploadImages') as $imageFileName) {
                $imagesPath[] = [
                    'path' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName,
                    'fileName' => $imageFileName,
                    'fileSize' => filesize($_SERVER['DOCUMENT_ROOT'] . $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName)
                ];
            }

        }

        if (!empty($imagesPath)) {
           return json_encode(['files' => $imagesPath]);
        } else {
            return json_encode(['files' => '']);
        }
    }
}
