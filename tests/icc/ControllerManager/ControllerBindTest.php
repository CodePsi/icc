<?php

//namespace icc;
//namespace icc\ControllerManager;


//use Icc\ControllerManager\ControllerBind;
use Icc\ControllerManager\ControllerBind;
use PHPUnit\Framework\TestCase;
require __DIR__ . "/../../../../vendor/autoload.php";
//include_once __DIR__ . "\..\..\..\ControllerManager\ControllerBind.php";

class ControllerBindTest extends TestCase
{
    public function testCallControllerMethod(): void {
        $this -> assertNotEmpty(ControllerBind::callControllerMethod("WriteOffAct", "generateWriteOffAct", array(null, null)));
    }

    public function testCreateClass(): void {
//        var_dump(get_declared_classes());
//        var_dump(spl_autoload_functions());
        var_dump($_SERVER['DOCUMENT_ROOT']);
//        var_dump(class_exists('WriteOffController'));
//        $class = new ReflectionClass('Icc\ControllerManager\Controller');
    }
}