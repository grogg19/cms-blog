<?php
/**
 * класс MigrationController
 *
 */

namespace App\Migrations;

use App\Controllers\Controller;
use App\View;

/**
 * Class MigrationLoader
 * @package App\Controllers\System
 */
class MigrationLoader extends Controller
{
    /**
     * Запускает миграции
     */
    public function makeMigrations()
    {
        $migration = new Migration(new MigrationMySql());

        if (!empty($migration->getMigrationFiles())) {
            $migration->makeMigration();

            (new View('migrating_done', ['message' => 'БД в актуальном состоянии.']))->render();
            exit();
        }

    }
}
