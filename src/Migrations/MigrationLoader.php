<?php
/**
 * класс MigrationController
 *
 */

namespace App\Migrations;

use App\Renderable;
use App\View;

/**
 * Class MigrationLoader
 * @package App\Controllers\System
 */
class MigrationLoader implements Renderable
{
    /**
     * Запускает миграции
     */
    public function makeMigrations()
    {
        $migration = new Migration(new MigrationMySql());

        if (!empty($migration->getMigrationFiles())) {
            $migration->makeMigration();
        }

        $this->render();
        exit();

    }

    public function render()
    {
        (new View('migrating_done', ['message' => 'БД в актуальном состоянии.']))->render();
    }
}
