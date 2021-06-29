<?php
/**
 * Класс Конфигураций Config
 */

namespace App;

final class Config
{
    /**
     * @var Config
     */
    private static $instance;

    /**
     * @var array
     */
    private $configs = [];

    /**
     * Config constructor.
     */
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

    /**
     * @param $key
     * @param array $default
     * @return array
     */
    public function getConfig($key, array $default = []): array
    {
        return $this->configs[$key] ?? $default;
    }

    /**
     * @param $key
     * @param $value
     * @return Config
     */
    public function setConfig($key, $value): Config
    {
        $this->configs[$key] = $value;
        return $this;
    }

    /**
     * @param $config
     * @param null $default
     * @return array
     */
    public function get($config, $default = null): array
    {
        return array_get($this->configs, $config, $default);
    }
}
