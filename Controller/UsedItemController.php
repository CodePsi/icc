<?php


namespace Icc\Controller;


use Icc\Dao\UsedItemDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\UsedItem;

class UsedItemController
{
    public static function addNewUsedItem(int $requestId, int $itemId, int $amount, string $date) {
        $dao = new UsedItemDao();
        $entry = new UsedItem(-1, $requestId, $itemId, $amount, $date, '');
        try {
            $dao->save($entry);
            echo "Success";
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getAllUsedItems() {
        $dao = new UsedItemDao();
        echo json_encode($dao -> convertArrayToModels($dao->getAll()));
    }
}