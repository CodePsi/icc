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
use Icc\PdfGenerator\MPDFGenerator;

class RequestController
{

    public static function getAllRequests() {
        $dao = new RequestDao();
        echo json_encode($dao -> convertArrayToModels($dao -> getAll()));
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
            $technicalTicketNeeded = $requestInstance -> getTechnicalTicketNeeded();
            $requestDate = explode(' ', $requestInstance -> getDate());
            echo base64_encode($pdf -> generateRequest($id, $requestDate[0], $requestDate[1], $employeeInstance -> getSurname() . ' ' . $employeeInstance -> getName() . ' ' . $employeeInstance -> getPatronymic(),
                $employeeInstance -> getPosition(), $employeeInstance -> getContactNumber(), $requestInstance -> getBuilding(),
                $requestInstance -> getAuditorium(), $inventoryNumbers, $requestInstance -> getReason(), $technicalTicketNeeded));
        } catch (NotFoundItemException $e) {
            echo $e;
        }

    }

    public static function addRequest(int $employeeId, string $building, string $auditorium, string $reason, string $date, int $status, int $technicalTicketNeeded)
    {
        $requestDao = new RequestDao();
        $request = new Request(-1, $employeeId, $building, $auditorium, $reason, $date, intval($status), $technicalTicketNeeded);
        try {
            $requestDao->save($request);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getRequest($id)
    {
        $dao = new RequestDao();
        try {
            echo json_encode($dao -> get(intval($id)));
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function updateRequest(int $id, int $employeeId, string $building, string $auditorium, string $reason, string $date, int $status, int $technicalTicketNeeded)
    {
        $dao = new RequestDao();
        $request = new Request($id, $employeeId, $building, $auditorium, $reason, $date, intval($status), $technicalTicketNeeded);
        try {
            $dao->update($request);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function closeRequest($usedItems, $requestId)
    {
        $usedItemDao = new UsedItemDao();
        $stockItemDao = new StockItemDao();
        $requestDao = new RequestDao();
        if (count($usedItems) == 0) {
            try {
                $request = $requestDao->get($requestId);
                $request->setStatus(1);
                $requestDao->update($request);
            } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
            }
        } else {
            for ($i = 0; $i < count($usedItems); $i++) {
                try {
                    $usedItem = new UsedItem(-1, $requestId, $usedItems[$i]["itemId"], $usedItems[$i]["count"], date('Y-m-d H:i:s'), $usedItems[$i]['inventoryNumber']);
                    $stockItem = $stockItemDao->get($usedItems[$i]["itemId"]);
                    $stockItem->setAmount($stockItem->getAmount() - $usedItems[$i]["count"]);
                    $stockItem -> setTotal($stockItem -> getAmount() * $stockItem -> getPrice());
                    $stockItemDao->update($stockItem);
                    $request = $requestDao->get($requestId);
                    $request->setStatus(1);
                    $requestDao->update($request);
                    $usedItemDao->save($usedItem);
                } catch (IncorrectObjectTypeException | NotFoundItemException $e) {
                    echo $e;
                }
            }
        }
    }

    public static function deleteRequest($id) {
        $dao = new RequestDao();
        $usedItemDao = new UsedItemDao();
        $stockItemDao = new StockItemDao();
        try {
            $usedItems = $usedItemDao->convertArrayToModels($usedItemDao -> where(array('request_id'), array($id), array('=')));
            if (isset($usedItems) && count($usedItems) > 0) {
                foreach ($usedItems as $usedItem) {
                    $stockItem = $stockItemDao -> get($usedItem -> getItemId());
                    $stockItem -> setAmount($stockItem -> getAmount() + $usedItem -> getAmount());
                    $stockItem -> setTotal($stockItem -> getAmount() * $stockItem -> getPrice());
                    $stockItemDao -> update($stockItem);
                    $usedItemDao -> delete($usedItem -> getId());
                }
            }
            $dao -> delete($id);
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }
}