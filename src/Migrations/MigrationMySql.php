<?php


namespace App\Migrations;

use Illuminate\Database\Capsule\Manager as DB;
use SplFileObject;
use App\View;

/**
 * Class MigrationMySql
 * @package App\Migrations
 */
class MigrationMySql implements Migratable
{
    /**
     * импортирует данные из миграций в бд
     * @param array $files
     */
    public function migrate(array $files): void
    {
        (new View('migrating_process', ['message' => 'Начинаем миграцию...']))->render();

        foreach ($files as $file) {
            if (file_exists($file)) {
                $splObject = new SplFileObject($file);
                (new View('migrating_process', ['message' => 'Записываем данные из ' . $splObject->getBasename()]))->render();

                $content = $splObject->fread($splObject->getSize());

                // получаем массив sql из файла
                $sqlArray = explode(';', $content);

                // Выполняем sql
                foreach ($sqlArray as $sql) {
                    $sql = trim($sql);
                    if (!empty($sql)) {
                        DB::connection()->statement($sql . ';');
                    }
                }

                // записываем выполненную миграцию в таблицу migrations
                DB::table('migrations')->insert([
                    'name' => $splObject->getBasename()
                ]);

            }

        }
        (new View('migrating_process', ['message' => 'Миграция завершена.']))->render();
    }

    /**
     * Проверяет наличие таблицы migrations в бд и возвращает true | false
     * @return bool
     */
    public function checkMigrationsTable(): bool
    {
        return !DB::schema()->hasTable('migrations');
    }
}
