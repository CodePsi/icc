<?php
//include_once "PDFGenerator.php";
//include_once "ControllerManager/Controller.php";
namespace Icc\Controller;

use DateTime;
use Exception;
use Icc\Dao\UsedItemDao;
use Icc\Dao\WriteOffActDao;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\WriteOffAct;
use Icc\MPDFGenerator;

require __DIR__ . "/../../vendor/autoload.php";
class WriteOffController
{


    public static function generateWriteOffAct(int $id, array $items = array(), array $members = array()) {
        $pdf = MPDFGenerator::getInstance();
        $dao = new WriteOffActDao();
        $usedItemDao = new UsedItemDao();
        try {
            $writeOffActInstance = $dao -> get($id);
            $items = $usedItemDao -> where(array("date", "date"),
                array("'" . $writeOffActInstance -> getStartDate() . "'", "'" . $writeOffActInstance -> getEndDate() . "'"), array(">=", "<="));
            $items = WriteOffAct::getItemsForWriteOffActTable($items);
        } catch (NotFoundItemException $e) {
        }
//        header("Content-Disposition: attachment; filename=S");

        echo base64_encode($pdf -> writeOffAct($items, $members));
//        echo base64_encode($v -> generateRequest(0, '', '', '', '', '', '', '', 1, 'SomeTask'));
    }

    public static function addNewWriteOffAct(string $startDate, string $endDate) {
        $dao = new WriteOffActDao();
        try {
            $startDateTimeInstance = new DateTime($startDate);
            $endDateTimeInstance = new DateTime($endDate);
            $writeOffAct = new WriteOffAct(-1, $startDateTimeInstance, $endDateTimeInstance);
            $dao->save($writeOffAct);
            echo "Success";
        } catch (Exception | IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getAllWriteOffActs() {
        $dao = new WriteOffActDao();
        echo json_encode($dao -> getAll());
    }

    public static function deleteWriteOffAct($id) {
        $dao = new WriteOffActDao();
        try {
            $dao->delete(intval($id));
            echo "Success";
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }


}

//WriteOffController::generateWriteOffAct();
//echo "Test";
//echo 'window.location = "WriteOffController.php"';

//For correct passing data between a client and server there's a need to encode it to base64 and then pass it, because we could overflow buffer.
