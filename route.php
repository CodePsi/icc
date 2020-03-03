<?php
namespace Icc;
use Icc\Controller\EmployeeController;
use Icc\Controller\RequestController;
use Icc\Controller\StockItemController;
use Icc\Controller\WriteOffController;
use Icc\Route\Route;
//use Icc\WriteOffController;
//use Icc\Route;
use Icc\View;
use Icc\Json\JSON;
use function Icc\Json\json;
require __DIR__ . "/../vendor/autoload.php";

//include_once "Route/Route.php";
//include_once "Render/View.php";
//include_once "WriteOffController.php";
//include_once "Utils.php";

//General
Route::setRoute("errorPage", function () {
    echo "Error 404! This page does not exist!";
});
Route::setRoute("/icc/hello", function() {
     return new View("views/error404.html");
});
Route::setRoute("/icc/writeOffPage", function () {
    return new View("views/writeOffControlPage.html");
});
Route::setRoute("/icc/stock", function () {
    return new View("views/stock.html");
});
Route::setRoute("/icc/requestsPanel", function () {
    return new View("views/requestsControlPage.html");
});
Route::setRoute("/icc/requestPdf", function () {
    return new View("views/requestPdf.html");
});

//Write Off Act Routes
Route::post("/icc/writeOffPage/generateWriteOffAct", function () {
    WriteOffController::generateWriteOffAct($_POST['writeOffActId']);
});
Route::post("/icc/writeOffPage/addNewWriteOffAct", function () {
    WriteOffController::addNewWriteOffAct($_POST['startDate'], $_POST['endDate']);
});
Route::setRoute("/icc/writeOffPage/getAllWriteOffActs", function () {
    WriteOffController::getAllWriteOffActs();
});
Route::setRoute("/icc/writeOffPage/deleteWriteOffAct", function () {
    WriteOffController::deleteWriteOffAct($_POST['writeOffActId']);
});

//Stock Routes
Route::setRoute("/icc/stock/addNewStockItem", function () {
    StockItemController::addNewStockItem($_POST['name'], $_POST['surname'], $_POST['patronymic'], $_POST['status'], $_POST['contactNumber'], $_POST['position'], $_POST['responsible']);
});
Route::setRoute("/icc/stock/getAllStockItems", function () {
    StockItemController::getAllStockItems();
});
Route::get("/icc/stock/getStockItem", function () {
    StockItemController::getStockItem($_GET['id']);
});

//Employees' Routes
Route::setRoute("/icc/employee/getAllEmployees", function () {
    EmployeeController::getAllEmployees();
});

//Requests' Routes
Route::get("/icc/requestPanel/getAllRequests", function () {
    RequestController::getAllRequests();
});

Route::get("/icc/requests/getRequest", function () {
    RequestController::getRequest($_GET['id']);
});

Route::get("/icc/requests/generateRequestDocument", function () {
    RequestController::generateRequestDocument($_GET['id']);
});

Route::patch("/icc/requests/updateRequest", function () {
    RequestController::updateRequest(json('id'), json('employee'), json('building'), json('auditorium'), json('cause'), json('date'), json('status'));
});

Route::post("/icc/requests/addRequest", function () {
    RequestController::addRequest(json('employee'), json('building'), json('auditorium'), json('cause'), json('date'), json('status'));
});

Route::post("/icc/requests/closeRequest", function () {
    RequestController::closeRequest(json("usedItems"), json("inventoryNumbers"), json("requestId"));
});

//UsedItems' Routes
Route::post("/icc/usedItems/addNewEntry", function () {

});

Route::setRoute("/icc/test", function () {
    var_dump($_SERVER);
});