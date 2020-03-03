<?php
namespace Icc\Route;

use Closure;
//use Icc\ControllerBind;
//use Icc\Render;
use Icc\ControllerManager\ControllerBind;
use Icc\Render\Render;

require __DIR__ . "/../../vendor/autoload.php";

//include_once __DIR__ . "\..\Render\Render.php";
//include_once __DIR__ . "\..\ControllerManager\Controller.php";
//include_once __DIR__ . "\..\ControllerManager\ControllerBind.php";

class Route
{
    private static $routes = array();

    private static $getRequests = array();
    private static $postRequests = array();
    private static $putRequests = array();
    private static $patchRequests = array();

    /**
     * Route constructor.
     */
    private function __construct()
    {

    }

    protected function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * Append a new or replace old route to the callback function.
     *
     * @param $url
     * @param Closure $function
     */
    public static function setRoute($url, Closure $function) {
        //TODO Passing class (controller) instead of a function and further working with controller's methods.
        self::$routes[$url] = $function;
    }

    public static function get(string $url, Closure $callback) {
        self::$getRequests[$url] = $callback;
    }

    public static function post(string $url, Closure $callback) {
        self::$postRequests[$url] = $callback;
    }

    public static function put(string $url, Closure $callback) {
        self::$putRequests[$url] = $callback;
    }

    public static function patch(string $url, Closure $callback) {
        self::$patchRequests[$url] = $callback;
    }


    /**
     * Check if passed url (route) is existed.
     *
     * @param $url string
     * @param $array array
     * @return bool
     */
    private static function has(string $url, array $array) {
        return array_key_exists($url, $array);
    }

    /**
     * Render and run passed url.
     *
     * @param $url
     */
    public static function runPath($url) {
        $urlSplit = explode('?', $url);
        $args = $urlSplit[1]; //Checking for ending. TODO refactor it for url like something/bla/{id} and passing ID somehow
        $url = $urlSplit[0];
        if (self::has($url, self::$routes)) {
            Render::render(self::$routes[$url] -> call(new Route()));
        } else if (self::has($url, self::$postRequests)) {
            if ($_SERVER['REQUEST_METHOD'] === "POST") {
                Render::render(self::$postRequests[$url] -> call(new Route()));
            }
        } else if (self::has($url, self::$getRequests)) {
            if ($_SERVER['REQUEST_METHOD'] === "GET") {
                Render::render(self::$getRequests[$url] -> call(new Route()));
            }
        } else if (self::has($url, self::$putRequests)) {
            if ($_SERVER['REQUEST_METHOD'] === "PUT") {
                Render::render(self::$putRequests[$url] -> call(new Route()));
            }
        } else if (self::has($url, self::$patchRequests)) {
            if ($_SERVER['REQUEST_METHOD'] === "PATCH") {
                Render::render(self::$patchRequests[$url]->call(new Route()));
            }
        } else {
            self::$routes["errorPage"] -> call(new Route());
        }
    }


}