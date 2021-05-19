<?php


namespace App\Controllers\System;

use App\Controllers\Controller;
use App\Migrations\Migration;
use App\Migrations\MigrationMySql;
use App\View;

class MigrationController extends Controller
{
    public function makeMigrations()
    {
        $migration = new Migration(new MigrationMySql());

        if(!empty($migration->getMigrationFiles())) {
            $migration->makeMigration();
        }

        (new View('migrating_process', ['message' => 'БД в актуальном состоянии.']))->render();
    }
}