<?php
/**
 * Класс Abstract Provider
 */

namespace App\Service;

use App\DI\DI;

abstract class AbstractProvider
{
    /**
     * @var здесь будут храниться экземпляры \App\DI\DI
     */
    protected $di;

    /**
     * AbstractProvider constructor.
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    public abstract function init();
}
