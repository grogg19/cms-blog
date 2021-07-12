<?php

namespace App\Reflection;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ReflectionArguments
{

    /**
     * @var ReflectionMethod
     */
    private $methodInfo;

    /**
     * ReflectionArguments constructor.
     * @param $controller
     * @param string $method
     * @throws ReflectionException
     */
    public function __construct($controller, string $method)
    {
        $this->methodInfo = new ReflectionMethod($controller, $method);
    }

    /**
     * возвращает массив объектов для метода контроллера
     * @return array
     * @throws ReflectionException
     */
    public function getParameters(): array
    {
        $parameters = [];
        foreach ($this->methodInfo->getParameters() as $parameter) {

            $reflectionClass = new ReflectionClass($parameter->getType()->getName());

            if ($reflectionClass->isInstantiable()) {
                $parameters[] = $reflectionClass->newInstance();
            }

        }

        return $parameters;

    }

}
