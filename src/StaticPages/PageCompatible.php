<?php

namespace App\StaticPages;

/**
 * Interface PageCompatible
 * @package App\StaticPages
 */
interface PageCompatible
{
    /**
     * @param string $url
     */
    public function create(string $url): void;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param array $data
     * @return mixed
     */
    public function saveData(array $data): bool;

    /**
     * @return mixed
     */
    public function delete(): bool;

    /**
     * @return bool
     */
    public function checkExistUrl(string $url): bool;
}