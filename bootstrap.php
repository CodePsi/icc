<?php

/**
 * Create the Application
 * -------------------------
 * Initialization and binding.
 *
 */

use Icc\Response\ResponseDataReceiver;
use Icc\Route\Router;
require __DIR__ . "/vendor/autoload.php";

require "route.php";

ResponseDataReceiver::initializeJson();
session_start();
$router = Router::createRouter();
\Icc\Database\DBConnector::setDatabaseConfiguration(new \Icc\Database\DatabaseConfiguration());
//\Icc\DependencyInjection\Container\DIContainer::initiateCoreContainer(\Icc\DependencyInjection\Container\ServiceInjectionContainer::class);

require "container.php";


if (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] != 200) {
    $router -> runPath('errorPage' . $_SERVER['REDIRECT_STATUS']);
}
$router -> runPath($_SERVER['REQUEST_URI']);
