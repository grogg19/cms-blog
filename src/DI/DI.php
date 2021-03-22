<?php
/**
 * Паттерн Dependency Injection - Контейнер
 */

namespace App\DI;

class DI
{
    /**
     * @var array
     */
    private $container = [];


    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setContainer($key, $value)
    {
        $this->container[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getContainer($key)
    {
        return $this->hasKey($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key)
    {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }
}
