<?php


namespace Icc\Controller;


use Icc\Dao\StockItemDao;
use Icc\DBConnector;
use Icc\Model\Employee;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\StockItem;
use function Icc\Response\queryParameter;

class StockItemController
{

    public static function addNewStockItem($itemName, $type, $unit, $amount, $price, $total, $responsiblePersonEmployeeId, $code) {
        $dao = new StockItemDao();
        $entity = new StockItem(-1, $itemName, $type, $unit, $amount, $price, $total, $responsiblePersonEmployeeId, $code);
        try {
            $dao->save($entity);
            echo "Success";
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getStockItem($id) {
        $dao = new StockItemDao();
        try {
            echo json_encode($dao -> get(intval($id)));
        } catch (NotFoundItemException $e) {
            echo $e;
        }

        return null;
    }

    public static function getAllStockItems() {
        $dao = new StockItemDao();
        echo json_encode($dao -> convertArrayToModels($dao -> getAll()));
    }

    public static function updateStockItem($id, $itemName, $type, $unit, $amount, $price, $total, $responsible, $code)
    {
        $stockItemDao = new StockItemDao();
        try {
            if ($responsible === 'NULL') {
                $responsible = -1;
            }
            $stockItem = new StockItem($id, $itemName, $type, $unit, $amount, $price, $total, $responsible, $code);
            $stockItemDao -> update($stockItem);
            echo DBConnector::getStatus();
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function deleteStockItem($id)
    {
        $dao = new StockItemDao();
        try {
            $dao -> delete($id);
            echo "Success";
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

}