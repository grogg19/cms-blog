<?php
/**
 * класс MigrationController
 *
 */

namespace App\Controllers\System;

use App\Controllers\Controller;
use App\Migrations\Migration;
use App\Migrations\MigrationMySql;
use App\View;

/**
 * Class MigrationController
 * @package App\Controllers\System
 */
class MigrationController extends Controller
{
    /**
     * Запускает миграции
     */
    public function makeMigrations()
    {
        $migration = new Migration(new MigrationMySql());

        if(!empty($migration->getMigrationFiles())) {
            $migration->makeMigration();

            (new View('migrating_done', ['message' => 'БД в актуальном состоянии.']))->render();
        }

    }
}
