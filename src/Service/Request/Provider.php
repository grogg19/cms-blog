<?php
/**
 * Провайдер Request
 */

namespace App\Service\Request;

use App\Request\Request;
use App\Service\AbstractProvider;

class Provider extends AbstractProvider
{
    /**
     * @var string
     */
    public $serviceName = 'request';

    /**
     * @return mixed
     */
    public function init()
    {
        $request = new Request();
        $this->di->setContainer($this->serviceName, $request);
    }
}
