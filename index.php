<?php

error_reporting(E_ALL);
ini_set('display_errors',true);

require_once 'bootstrap.php';

use App\Application;

$router = require_once 'routes/web.php';

// создаем приложение
$application = new Application($router);

// Запуск приложения
$application->run();

