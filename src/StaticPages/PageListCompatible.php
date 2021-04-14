<?php

namespace App\StaticPages;

/**
 * Interface PageListCompatible
 * @package App\StaticPages
 */
interface PageListCompatible
{
    /**
     * Метод возвращает список страниц
     * @return array
     */
    public function list(): array;
}
