<?php


namespace Icc\Controller;


use Icc\Dao\EmployeeDao;
use Icc\Dao\InventoryNumberDao;
use Icc\Dao\RequestDao;
use Icc\Dao\StockItemDao;
use Icc\Dao\UsedItemDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\InventoryNumber;
use Icc\Model\NotFoundItemException;
use Icc\Model\Request;
use Icc\Model\UsedItem;
use Icc\MPDFGenerator;

class RequestController
{

    public static function getAllRequests() {
        $dao = new RequestDao();
        echo json_encode($dao -> getAll());
    }

    public static function generateRequestDocument(int $id) {
        $pdf = MPDFGenerator::getInstance();
        $inventoryNumberDao = new InventoryNumberDao();
        $requestDao = new RequestDao();
        $employeeDao = new EmployeeDao();
        try {
            $requestInstance = $requestDao -> get($id);
            $inventoryNumbers = $inventoryNumberDao -> where(array('request_id'), array($id), array('='));
            $employeeInstance = $employeeDao -> get($requestInstance -> getEmployeeId());
            $requestDate = explode(' ', $requestInstance -> getDate());
            echo base64_encode($pdf -> generateRequest($id, $requestDate[0], $requestDate[1], $employeeInstance -> getName() . ' ' . $employeeInstance -> getSurname() . ' ' . $employeeInstance -> getPatronymic(),
                $employeeInstance -> getPosition(), $employeeInstance -> getContactNumber(), $requestInstance -> getBuilding(),
                $requestInstance -> getAuditorium(), $inventoryNumbers, $requestInstance -> getReason()));
        } catch (NotFoundItemException $e) {
        }

    }

    public static function addRequest(int $employeeId, string $building, string $auditorium, string $reason, string $date, int $status)
    {
        $requestDao = new RequestDao();
        $request = new Request(-1, $employeeId, $building, $auditorium, $reason, $date, $status);
        try {
            $requestDao->save($request);
            echo 'Object has been added successfully!';
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getRequest($id)
    {
        $dao = new RequestDao();
        try {
//            echo $dao -> get(intval($id)) -> getId();
            echo $dao -> get(intval($id)) -> toJson();
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function updateRequest(int $id, int $employeeId, string $building, string $auditorium, string $reason, string $date, int $status)
    {
        $dao = new RequestDao();
        $request = new Request($id, $employeeId, $building, $auditorium, $reason, $date, $status);
        try {
            $dao->update($request);
            echo "Success";
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function closeRequest($usedItems, $inventoryNumbers, $requestId)
    {
        $usedItemDao = new UsedItemDao();
        $stockItemDao = new StockItemDao();
        $inventoryNumberDao = new InventoryNumberDao();
        for ($i = 0; $i < count($usedItems); $i++) {
            try {
                $usedItem = new UsedItem(-1, $requestId, $usedItems[$i]["select"], $usedItems[$i]["input"], date('Y-m-d H:i:s'), '');
                $stockItem = $stockItemDao -> get($usedItems[$i]["select"]);
                $stockItem -> setAmount($stockItem -> getAmount() - $usedItems[$i]["input"]);
                $stockItemDao -> update($stockItem);
                $usedItemDao->save($usedItem);
            } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
                echo $e;
            }
        }
        for ($i = 0; $i < count($inventoryNumbers); $i++) {
            try {
                $inventoryNumber = new InventoryNumber(-1, $requestId, $inventoryNumbers[$i]);
                $inventoryNumberDao->save($inventoryNumber);
            } catch (IncorrectObjectTypeException $e) {
                echo $e;
            }
        }
    }
}