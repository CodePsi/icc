<?php

//use icc\ControllerBind;
namespace icc;
use Icc\ControllerManager\ControllerBind;
use LogicException;
use ReflectionClass;
use ReflectionException;
//use Icc\ClassConfigurator;
require __DIR__ . "/../../vendor/autoload.php";

include_once __DIR__ . "\..\ControllerManager\ControllerBind.php";

class Controller2
{
    private function __construct()
    {
        $class = new ReflectionClass('Controller');
        var_dump($class -> getConstructor());
    }

    public static function test() {
        $class = '\\Icc\\ClassConfigurator';
        $c = new $class;
    }
}

//Controller2::test();
$r = new ReflectionClass('stdClass');
var_dump($r);
//ControllerBind::callControllerMethod("\Icc\ClassConfigurator", "generateWriteOffAct", array(null, null));
//$class = new ReflectionClass("ClassConfigurator");
//try {
//require_once "./ClassConfigurator.class.php";
//print_r(get_declared_classes());
//call_user_func(array('Icc\ClassConfigurator', 'get'));
//$c1 = new $class();
//$ReflectedClass = new ReflectionClass(\Icc\ClassConfigurator::class);
//} catch (ReflectionException $logicDuh) {
//    print_r($logicDuh);
//}
//include(__DIR__ . "\..\WriteOffController.php");
//echo PHP_EOL . WriteOffController::class;
//print_r(get_declared_classes());

//phpinfo();
//Reflection::export(new ReflectionClass('WriteOffController'));