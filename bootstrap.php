<?php

/**
 * Create the Application
 * -------------------------
 * Initialization and binding.
 *
 */

use Icc\Route\Route;
require __DIR__ . "/../vendor/autoload.php";

require "route.php";


\Icc\Json\JSON::initializeJson();

//include "Json/JSON.php";

if ($_SERVER['REDIRECT_STATUS'] != 200) {
    Route::runPath('errorPage' . $_SERVER['REDIRECT_STATUS']);
}
Route::runPath($_SERVER['REQUEST_URI']);
