<?php
namespace Icc;
use Icc\Route\Route;
//use Icc\WriteOffController;
//use Icc\Route;
use Icc\View;

require __DIR__ . "/../vendor/autoload.php";

//include_once "Route/Route.php";
//include_once "Render/View.php";
//include_once "WriteOffController.php";
//include_once "Utils.php";

Route::setRoute("errorPage", function () {
    echo "Error 404! This page does not exist!";
});
Route::setRoute("/icc/hello", function() {
     return new View("views/error404.html");
});
Route::setRoute("/icc/writeOffPage", function () {
    return new View("views/writeOffControlPage.html");
});

Route::setRoute("/icc/writeOffPage/generateWriteOffAct", function () {
    WriteOffController::generateWriteOffAct(array(), array());
});
//Route::post("/icc/writeOffPage/generateWriteOffAct", "WriteOffController@generateWriteOffAct");