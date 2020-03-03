<?php
namespace Icc\ControllerManager;
//namespace Icc\ControllerManager;
use Controller;
use ControllerNotExtended;
use Exception;
use NoSuchMethodException;
use ReflectionClass;
use ReflectionMethod;

//include_once "Controller.php";

class ControllerBind
{
    /**
     * Execute (call) passed class's function with parameters.
     *
     * @param string $class
     * @param string $method
     * @param array $params $_POST
     * @return mixed|string Result of executed function.
     */
    public static function callControllerMethod(string $class, string $method, array $params) {
        try {
            $reflectionClass = new ReflectionClass($class);

            $parent = $reflectionClass -> getParentClass(); //if there's no any parent class return false.
            if (!$parent || !($parent instanceof Controller)) {
                throw new ControllerNotExtended($reflectionClass -> getName() . " does not extend the Controller class. ");
            }
            $methods = $reflectionClass -> getMethods(ReflectionMethod::IS_PUBLIC); //Only search for public methods.
            $methodForCall = self::findMethod($methods, $method);
            if ($methodForCall == false) {
                throw new NoSuchMethodException("The $method was not found in the {$reflectionClass -> getName()} controller.");
            }
            $classInstance = $reflectionClass -> newInstance();
            return call_user_func_array(array($classInstance, $methodForCall -> getName()), $params);

        } catch (Exception $e) {
            echo $e -> getMessage();
        }

        return "";
    }


    /**
     * @param array $reflectionMethods
     * @param $searchMethod
     * @return ReflectionMethod|false
     */
    private static function findMethod(array $reflectionMethods, $searchMethod) {
        $method = false;
        foreach ($reflectionMethods as $singleMethod) {
            if ($singleMethod -> getName() === $searchMethod) {
                $method = $singleMethod;
                break;
            }
        }

        return $method === null ? false : $method;

    }
}

