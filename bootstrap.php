<?php

require_once __DIR__ . '/vendor/autoload.php';


define('APP_DIR',  $_SERVER['DOCUMENT_ROOT']); // Корень проекта
define('VIEW_DIR',  $_SERVER['DOCUMENT_ROOT'] . '/layout/'); // Корень всех представлений
define('SITE_ROOT',  'http://' . $_SERVER['SERVER_NAME']); // Корень проекта

date_default_timezone_set('Europe/Moscow');