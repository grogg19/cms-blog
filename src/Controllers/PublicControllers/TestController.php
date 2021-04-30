<?php
/**
 * Created by PhpStorm.
 * User: diffe
 * Date: 05.11.2020
 * Time: 18:54
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;

use function Helpers\cleanJSTags;

class TestController extends Controller
{
    public string $name;
    public function test()
    {
//        $arrayOne = [1, 2, 3, 4, 5];
//        $arrayTwo = [1, 2, 3];
//
//        printArray(array_diff($arrayOne, $arrayTwo));
//        echo date(');

//        printArray('Контроллер для тестирования');
        //$text = "<script language='JavaScript1.5'>alert(123);</script><b>ggg</b><script>alert('fff')</script>";
        dump($this->name);

    }
}