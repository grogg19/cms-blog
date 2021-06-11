<?php

namespace App\Migrations;

/**
 * Interface migratable
 * @package App\Migrations
 */
interface Migratable
{
    /**
     * @param array $files
     */
    public function migrate(array $files): void;

    /**
     * @return bool
     */
    public function checkMigrationsTable(): bool;
}
