<?php
/**
 *  Настройка загрузки аватаров
 */
return [

    'pathToUpload' => '/upload/avatars',

    'mimeTypes' => [
        "image/jpeg",
        "image/pjpeg",
        "image/png",
        "image/x-png"
    ],

    'maxImageSize' => 2,

    'maxFilesAtOnce' => 1
];