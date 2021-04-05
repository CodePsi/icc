<?php
namespace Icc;
use Exception;
use Firebase\JWT\JWT;
use Icc\Controller\ActOfInstallationController;
use Icc\Controller\ActMembersController;
use Icc\Controller\EmployeeController;
use Icc\Controller\LoginController;
use Icc\Controller\RegistrationController;
use Icc\Controller\RequestController;
use Icc\Controller\SQLExecutorController;
use Icc\Controller\StockItemController;
use Icc\Controller\UsedItemController;
use Icc\Controller\UsersController;
use Icc\Controller\UsersDataUpdateController;
use Icc\Controller\WriteOffController;
use Icc\Facades\Route;
//use Icc\WriteOffController;
//use Icc\Route;
use Icc\Response\Redirect;
use Icc\Response\ResponseDataReceiver;
use Icc\Secure\AuthorizationMiddleware;
use Icc\Render\View;
use Icc\Json\JSON;
use function Icc\Response\json;
use function Icc\Response\queryParameter;
require __DIR__ . "/vendor/autoload.php";


Route::beforeEach(function (\Icc\Route\Route $to) {
    if (AuthorizationMiddleware::userAuthorized()) {
        if ($to -> getUri() == '') {
            Redirect::redirect('/icc/control-page/main');
        }
        else
            return $to -> call();
    }
    else if ($to -> getUri() !== '/icc/login' && $to -> getUri() !== '/icc/login/authorization') {
        Redirect::redirect('/icc/login');
    }
    return $to -> call();


});

//General
Route::setRoute("/icc/errorPage", function () {
    return new View("views/errorPage.html");
});
Route::setRoute("/icc/hello", function() {
     return new View("views/error404.html");
});
Route::setRoute("/icc/control-page/writeOffPage", function () {
    return new View("views/writeOffControlPage.html");
});
Route::setRoute("/icc/control-page/stock", function () {
    return new View("views/stock.html");
});
Route::setRoute("/icc/control-page/requests", function () {
    return new View("views/requestsControlPage.html");
});
Route::setRoute("/icc/requests/view/pdf/{id}", function () {
    return new View("views/requestPdf.html");
});

Route::setRoute("/icc/writeOffActs/view/pdf/{id}", function () {
    return new View("views/writeOffActPdf.html");
});

Route::setRoute("/icc/actsOfInstallation/view/pdf/{id}", function () {
    return new View("views/actOfInstallationPdf.html");
});

Route::setRoute("/icc/control-page/acts/members", function () {
    return new View("views/actMembers.html");
});

Route::setRoute("/icc/control-page/employees", function () {
    return new View("views/employeesControlPage.html");
});

Route::setRoute("/icc/control-page/main", function () {
    return new View("views/mainPage.html");
});

Route::setRoute("/icc/control-page/acts/actOfInstallation", function () {
    return new View("views/actOfInstallation.html");
});

Route::setRoute("/icc/login", function () {
    return new View("views/loginPage.html");
});

Route::setRoute("/icc/admin/sql-executor", function () {
    return new View("views/sqlExecutorPage.html");
});

Route::setRoute("/icc/admin/users-control-page", function () {
    return new View("views/adminUsersControlPage.html");
});

Route::setRoute("/icc/test", function () {
    return new View("views/test.html");
});

Route::setRoute("/icc/control-page/used-items-page", function() {
    return new View("views/usedItemsControlPage.html");
});

//Write Off Acts' Routes
Route::get("/icc/writeOffActs/pdf/generator/{id}", function ($id) {
    WriteOffController::generateWriteOffAct($id);
});

Route::post("/icc/writeOffActs/", function () {
    WriteOffController::addNewWriteOffAct(json('startDate'), json('endDate'), json('responsiblePerson'));
});

Route::get("/icc/writeOffActs/", function () {
    WriteOffController::getAllWriteOffActs();
});

Route::delete("/icc/writeOffActs/{id}", function ($id) {
    WriteOffController::deleteWriteOffAct($id);
});

Route::get("/icc/writeOffActs/{id}", function ($id) {
    WriteOffController::getWriteOffAct($id);
});

Route::put("/icc/writeOffActs/{id}", function ($id) {
    WriteOffController::updateWriteOffAct($id, json('startDate'), json('endDate'), json('responsiblePerson'));
});

//Acts Of Installation' Routes
Route::get("/icc/actsOfInstallation/pdf/generator/{id}", function ($id) {
    ActOfInstallationController::generateActOfInstallation($id);
});

Route::post("/icc/actsOfInstallation/", function () {
    ActOfInstallationController::addNewActOfInstallation(json('startDate'), json('endDate'), json('month'), json('responsiblePerson'));
});

Route::get("/icc/actsOfInstallation/", function () {
    ActOfInstallationController::getAllActsOfInstallation();
});

Route::delete("/icc/actsOfInstallation/{id}", function ($id) {
    ActOfInstallationController::deleteActOfInstallation($id);
});

Route::get("/icc/actsOfInstallation/{id}", function ($id) {
    ActOfInstallationController::getActOfInstallation($id);
});

Route::put("/icc/actsOfInstallation/{id}", function ($id) {
    ActOfInstallationController::updateActOfInstallation($id, json('startDate'), json('endDate'), json('month'), json('responsiblePerson'));
});

//Stock Routes
Route::post("/icc/stock/", function () {
        StockItemController::addNewStockItem(json('itemName'), json('type'), json('unit'), json('amount'), json('price'), json('total'), json('responsible'), json('code'));
});
Route::get("/icc/stock/", function () {
    StockItemController::getAllStockItems();
});
Route::get("/icc/stock/{id}", function ($id) {
    StockItemController::getStockItem($id);
});

Route::put("/icc/stock/{id}", function ($id) {
    StockItemController::updateStockItem($id, json('itemName'), json('type'), json('unit'), json('amount'), json('price'), json('total'), json('responsible'), json('code'));
});

Route::delete("/icc/stock/{id}", function ($id) {
    StockItemController::deleteStockItem($id);
});

//Employees' Routes
Route::get("/icc/employees/", function () {
    EmployeeController::getAllEmployees();
});

Route::post("/icc/employees/", function () {
    EmployeeController::addNewEmployee(json('name'), json('surname'), json('patronymic'), json('status'), json('contactNumber'), json('position'), json('responsible'));
});

Route::get("/icc/employees/{id}", function ($id) {
    EmployeeController::getEmployee($id);
});

Route::put("/icc/employees/{id}", function ($id) {
    EmployeeController::updateEmployee($id, json('name'), json('surname'), json('patronymic'), json('status'), json('contactNumber'), json('position'), json('responsible'));
});

Route::delete("/icc/employees/{id}", function ($id) {
    EmployeeController::deleteEmployee($id);
});

//Requests' Routes
Route::get("/icc/requests/", function () {
    RequestController::getAllRequests();
});

Route::get("/icc/requests/{id}", function ($id) {
    RequestController::getRequest($id);
});

Route::get("/icc/requests/pdf/generator/{id}", function ($id) {
    RequestController::generateRequestDocument($id);
});

Route::put("/icc/requests/{id}", function ($id) {
    RequestController::updateRequest($id, json('employee'), json('building'), json('auditorium'), json('reason'), json('date'), json('status'), json('technicalTicketNeeded'));
});

Route::post("/icc/requests/", function () {
    RequestController::addRequest(json('employee'), json('building'), json('auditorium'), json('reason'), json('date'), json('status'), json('technicalTicketNeeded'));
});

Route::post("/icc/requests/close/{requestId}", function ($requestId) {
    RequestController::closeRequest(json("usedItems"), $requestId);
});

Route::delete("/icc/requests/{id}", function ($id) {
    RequestController::deleteRequest($id);
});

//UsedItems' Routes
Route::post("/icc/usedItems/addNewEntry", function () {

});

//ActMembers' Routes

Route::get("/icc/actMembers/{id}", function ($id) {
    ActMembersController::getActMember($id);
});

Route::get("/icc/actMembers/", function () {
    ActMembersController::getAllActMembers();
});

Route::post("/icc/actMembers/", function () {
    ActMembersController::addNewActMember(json("employeeId"), json("position"), json('actType'));
});

Route::delete("/icc/actMembers/{id}", function ($id) {
    ActMembersController::deleteActMember($id);
});

Route::put("/icc/actMembers/{id}", function ($id) {
    ActMembersController::updateActMember($id, json("employeeId"), json("position"), json("date"), json('actType'));
});

Route::put("/icc/actMembers/date", function () {
    ActMembersController::addDateToAllActMembers(json("date"));
});

Route::post("/icc/login/authorization", function () {
    LoginController::login(json("login"), json("password"));
});

Route::post("/icc/register", function () {
    RegistrationController::registerUser(json("login"), json("password"), json("name")); //TODO Add checking for special key to prevent bad things
});

Route::put("/icc/users/password/{id}", function ($id) {
    UsersDataUpdateController::updatePassword($id, json("password"));
});

Route::get("/icc/users/", function () {
    UsersController::getAllUsers();
});

Route::get("/icc/users/{id}", function ($id) {
    UsersController::getUser($id);
});

Route::put("/icc/users/{id}", function ($id) {
    UsersController::changeUser($id, json('login'), json('name'));
});

//Route::post("/icc/users/", function () {
//    UsersController::addNewUser(json('login'), json('password'), json('name'));
//});



Route::post('/icc/admin/sql-executor/execute', function () {
    SQLExecutorController::executeNativeSqlCommand(json('data'));
});

Route::get('/icc/usedItems/', function () {
    UsedItemController::getAllUsedItems();
});

//Route::middlewareGroup(Route::getAllRoutesExcept(array("/icc/login", "/icc/login/authorization", "/icc/errorPage", "/icc/register")), function () {
//
//    return AuthorizationMiddleware::userAuthorized();
//});