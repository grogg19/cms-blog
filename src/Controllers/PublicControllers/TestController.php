<?php
/**
 * Created by PhpStorm.
 * User: diffe
 * Date: 05.11.2020
 * Time: 18:54
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;


class TestController extends Controller
{
    public string $name;

    public function test()
    {
        phpinfo();
    }
}