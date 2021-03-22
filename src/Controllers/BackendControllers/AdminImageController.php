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

class AdminImageController
{
    private $configImages;

    public function __construct()
    {
        $this->configImages = Config::getInstance()->getConfig('images');
    }

    public function imageDestructor(array $fileNames = [])
    {

        if(!empty(Cookie::getArray('uploadImages'))) {
            $listFilesForDelete = array_diff($fileNames, $this->imageCheckAvailabilityInDb($fileNames));
        } else {
            $listFilesForDelete = $fileNames;
        }

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

    public function imageCheckAvailabilityInDb(array $fileNames = [])
    {
        foreach (Image::whereIn('file_name', $fileNames)->get() as $item) {
            $listImages[] = $item->file_name;
        }
        return (isset($listImages)) ? $listImages : [];
    }

    public function deleteImageFromDb($fileName)
    {
        return Image::where('file_name', $fileName)->delete();
    }


    public function cacheImageClean() {

        if(!empty(Cookie::getArray('uploadImages'))) {

            $this->imageDestructor(Cookie::getArray('uploadImages'));

            Cookie::delete('uploadImages');
        }
    }

    public function getImageNameFromStorages()
    {
        if(!checkToken()) {
            return json_encode(['error' => 'Ошибка доступа']);
        }
        if(!empty(request()->post('postId'))) {
            $result = Image::where('post_id', request()->post('postId'))->get('file_name');
           foreach ($result as $imageFileName) {
               $imagesPath[] = [
                   'path' => 'http://' . $_SERVER['SERVER_NAME'] . $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName['file_name'],
                   'fileName' => $imageFileName['file_name'],
                   'fileSize' => filesize($_SERVER['DOCUMENT_ROOT'] . $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName['file_name'])
               ];
           }
        }

        if(!empty(Cookie::getArray('uploadImages'))) {
            foreach (Cookie::getArray('uploadImages') as $imageFileName) {
                $imagesPath[] = [
                    'path' => 'http://' . $_SERVER['SERVER_NAME'] . $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName,
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
