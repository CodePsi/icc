<?php


namespace Icc\Controller;


use Icc\Dao\StockItemDao;
use Icc\Model\Employee;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\StockItem;

class StockItemController
{

    public static function addNewStockItem($itemName, $type, $amount, $price, $total, $responsiblePersonEmployeeId, $code) {
        $dao = new StockItemDao();
        $entity = new StockItem(-1, $itemName, $type, $amount, $price, $total, $responsiblePersonEmployeeId, $code);
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
            echo $dao -> get(intval($id)) -> toJson();
        } catch (NotFoundItemException $e) {
            echo $e;
        }

        return null;
    }

    public static function getAllStockItems() {
        $dao = new StockItemDao();
        $array = $dao -> getAll();
        for ($i = 0; $i < count($array); $i++) {
            $array[$i][0] = intval($array[$i][0]);
            $array[$i][2] = intval($array[$i][2]);
            $array[$i][3] = floatval($array[$i][3]);
            $array[$i][4] = floatval($array[$i][4]);
            $array[$i][5] = intval($array[$i][5]);
        }
        echo json_encode($array);
    }

}