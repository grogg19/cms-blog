<?php
/**
 * Класс Конфигураций Config
 */

namespace App;

use function  Helpers\array_get;

final class Config
{
    private static $instance;
    private $configs = [];

    private function __construct()
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/configs/";

        if($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $filename = explode('.',$file);
                    if(file_exists($dir . $file) && $filename[1] = "php")
                    {
                        $this->configs[$filename[0]] = require_once $dir . $file;
                    }
                }
            }
        }
    }

    /**
     * @return Config
     */
    public static function getInstance(): Config
    {
        if(null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function getConfig($key, $default = null)
    {
        return $this->configs[$key] ?? $default;
    }

    public function setConfig($key, $value)
    {
        $this->configs[$key] = $value;
        return $this;
    }

    public function get($config, $default = null)
    {
        return array_get($this->configs, $config, $default);
    }
}
