<?php
/**
 * Created by PhpStorm.
 * User: diffe
 * Date: 11.01.2021
 * Time: 18:24
 */

namespace App\FileSystem;

class File extends Filesystem
{

    public function delete($pathToFile)
    {
        if(file_exists($pathToFile)) {
            unlink($pathToFile);
        }
    }
}
