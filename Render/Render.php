<?php
namespace Icc\Render;

use Icc\Route\Route;

require __DIR__ . "/../../vendor/autoload.php";


include_once "View.php";

class Render
{
    private function __construct()
    {
    }

    /**
     * Render page if there was passed View, otherwise output passed string.
     *
     * @param $resource
     */
    public static function render($resource) {
        if ($resource instanceof View) {
            readfile($resource -> getResourceName());
        }
        else if ($resource instanceof Route) {
            echo "test";
        }
        //TODO Implement interaction with controllers. Return string and call some function for it from controller.
//        else if (is_string($resource)) {
//
//        }
        else {
            echo $resource;
        }
    }
}