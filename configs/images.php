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

    'maxImageSize' => 6,

    'maxFilesAtOnce' => 6
];