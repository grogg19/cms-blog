<?php

namespace App\Migrations;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Class Migration
 * @package App\Migrations
 */
class Migration
{
    /**
     * @var Migratable
     */
    private Migratable $migration;

    /**
     * Migration constructor.
     * @param Migratable $migration
     */
    public function __construct(Migratable $migration)
    {
        $this->migration = $migration;
    }

    /**
     * Возвращает массив файлов миграций
     * @param string $nameMigrationsFolder
     * @return array
     */
    public function getMigrationFiles(string $nameMigrationsFolder = 'migrations'): array
    {
        $migrationsFolder = APP_DIR . DIRECTORY_SEPARATOR . $nameMigrationsFolder;

        // Получаем список всех sql-файлов
        $allMigrationsFiles = glob($migrationsFolder . DIRECTORY_SEPARATOR . '*.sql');

        // проверяем наличие таблицы migrations
        if ($this->migration->checkMigrationsTable()) {
            // Если таблицы нет, то выводим список всех файлов миграций
            return $allMigrationsFiles;
        }

        $migrationsFilesExists = []; // Сюда сложим всей файлы миграций, которых нет в БД

        $migrations = DB::table('migrations')->get();

        foreach ($migrations as $migration) {
            array_push($migrationsFilesExists, $migrationsFolder . DIRECTORY_SEPARATOR . $migration->name);
        }
        return array_diff($allMigrationsFiles, $migrationsFilesExists);
    }

    /**
     *  запускает миграцию
     */
    public function makeMigration(): void
    {
        $this->migration->migrate($this->getMigrationFiles());
    }

    /**
     * возвращает миграцию
     * @return Migratable
     */
    public function getMigration(): Migratable
    {
        return $this->migration;
    }
}
