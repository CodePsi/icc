<?php
namespace Icc\Route;

use Closure;
//use Icc\ControllerBind;
//use Icc\Render;
//use Icc\ControllerManager\ControllerBind;
//use Icc\Secure\AuthorizationMiddleware;
//use Icc\Secure\JWTSecurity;

require __DIR__ . "/../../vendor/autoload.php";


class Route
{
    public static $routes = array();

    private $uri;
    private $regexUri;

    private $method;


    public $parameters = [];
    public $parameterNames = [];

    private $action;
    private $controller = [];

    private $router;

    /**
     * Route constructor.
     * @param string $method
     * @param string $uri
     * @param Closure $action
     */
    public function __construct($method, $uri, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
    }


    /**
     * @return mixed
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param mixed $router
     */
    public function setRouter($router): void
    {
        $this->router = $router;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getParameterNames(): array
    {
        return $this->parameterNames;
    }

    /**
     * @return Closure
     */
    public function getAction(): Closure
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getRegexUri()
    {
        return $this->regexUri;
    }

    /**
     * @param mixed $regexUri
     */
    public function setRegexUri($regexUri): void
    {
        $this->regexUri = $regexUri;
    }

    /**
     * @return array
     */
    public function getController(): array
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController(array $controller): void
    {
        $this->controller = $controller;
    }

    public function call() {
        return $this->getAction()->call($this, ...$this->getParameters());
    }



}