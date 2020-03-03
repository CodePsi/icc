<?php


namespace Icc\Controller;


use Icc\Dao\InventoryNumberDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\InventoryNumber;

class InventoryNumberController
{
    public static function addNewInventoryNumber(int $requestId, string $inventoryNumber) {
        $dao = new InventoryNumberDao();
        $entry = new InventoryNumber(-1, $requestId, $inventoryNumber);
        try {
            $dao -> save($entry);
            echo "Success";
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }
}