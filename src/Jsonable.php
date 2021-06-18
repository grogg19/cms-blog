<?php

namespace App;

/**
 * Interface Jsonable
 * @package App
 */
interface Jsonable
{
    /**
     * @return string
     */
    public function json(): string;
}
