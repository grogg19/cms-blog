<?php


namespace App\Migrations;

use App\Config;
use Illuminate\Database\Capsule\Manager as DB;
use SplFileObject;
use App\View;

class MigrationMySql implements Migratable
{
    /**
     * @param array $files
     */
    public function migrate(array $files): void
    {
        (new View('migrating_process', ['message' => 'Начинаем миграцию...']))->render();

        $dbConfig = Config::getInstance()->getConfig("db");

        foreach ($files as $file) {
            if(file_exists($file)) {
                $splObject = new SplFileObject($file);
                $content = $splObject->fread($splObject->getSize());
                (new View('migrating_process', ['message' => 'Записываем данные из ' . $splObject->getBasename()]))->render();
                // генерируем команду для запуска импорта sql-файла
                $command = sprintf('mysql -u%s -p%s -h %s -D %s < %s', $dbConfig['username'], $dbConfig['password'], $dbConfig['host'], $dbConfig['database'], $file);
                // Выполняем shell-скрипт
                shell_exec($command);

                DB::table('migrations')->insert([
                    'name' => $splObject->getBasename()
                ]);


            }
        }
        (new View('migrating_process', ['message' => 'Миграция завершена.']))->render();

    }

    /**
     * @return bool
     */
    public function checkMigrationsTable(): bool
    {
        return !DB::schema()->hasTable('migrations');
    }
}
