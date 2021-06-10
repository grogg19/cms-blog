<?php
/**
 *  Настройка загрузки картинок
 */
return [

    'pathToUpload' => '/upload/images',

    'mimeTypes' => [
        "image/jpeg",
        "image/pjpeg",
        "image/png",
        "image/x-png"
    ],

    'maxImageSize' => 2,

    'maxFilesAtOnce' => 5
];