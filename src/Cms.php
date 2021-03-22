<?php
/**
 * cms
 */

namespace App;


class Cms
{
    /**
     * @var
     */
    private $di;

    /**
     * Cms constructor.
     * @param $di
     */
    public function __construct($di)
    {
        $this->di = $di;
    }
}