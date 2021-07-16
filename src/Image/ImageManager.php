<?php
/**
 * Класс ImageManager
 */

namespace App\Image;

use App\Model\Image;
use App\Config;
use App\Cookie\Cookie;


/**
 * Class ImageManager
 * @package App\Image
 */
class ImageManager
{
    /**
     * @var array
     */
    private $configImages;

    /**
     * ImageManager constructor.
     */
    public function __construct()
    {
        $this->configImages = Config::getInstance()->getConfig('images');
    }

    /**
     * метод удаляет файлы изображений
     * @param array $fileNames
     * @return string
     */
    public function imageDestructor(array $fileNames = []): string
    {

        if (!empty(Cookie::getArray('uploadImages'))) {
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
            $images = Cookie::getArray('uploadImages')[key(Cookie::getArray('uploadImages'))];
        }


        if (!empty($images)) {

            $this->imageDestructor($images);

            Cookie::delete('uploadImages');
        }
    }

    /**
     * Возвращает массив изображений из хранилища
     * @return string
     */
    public function getImageNameFromStorages(): string
    {
        if (!checkToken()) {
            return json_encode(['error' => 'Ошибка доступа']);
        }

        if (!empty(request()->post('postId'))) {
            $result = Image::where('post_id', request()->post('postId'))->get('file_name');

            foreach ($result as $imageFileName) {
                $imagesPath[] = [
                    'path' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName['file_name'],
                    'fileName' => $imageFileName['file_name'],
                    'fileSize' => filesize($_SERVER['DOCUMENT_ROOT'] . $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName['file_name'])
                ];
            }
        }

        if (!empty(Cookie::getArray('uploadImages'))) {
            //dd(Cookie::getArray('uploadImages')[key(Cookie::getArray('uploadImages'))]);
            foreach (Cookie::getArray('uploadImages')[key(Cookie::getArray('uploadImages'))] as $imageFileName) {
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

    /**
     * Проверяет актуальность изображений в куки, если сессия закончилась, изображения удаляются
     */
    public function checkImageUploadActuality(): void
    {
        //if (!empty(Cookie::getArray('uploadImages')) && ((new Session())->get('postBusy') !== true || session_status() !== PHP_SESSION_ACTIVE) ) {
        if ((!empty(Cookie::getArray('uploadImages')) && Cookie::getArray('uploadImages')[key(Cookie::getArray('uploadImages'))] !== SITE_ROOT . $_SERVER['REQUEST_URI']) || session_status() !== PHP_SESSION_ACTIVE ) {

            $this->cacheImageClean();

        }
    }
}
