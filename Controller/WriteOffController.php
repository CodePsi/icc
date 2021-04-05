<?php
namespace Icc\Controller;

use DateTime;
use Exception;
use Icc\Dao\ActMembersDao;
use Icc\Dao\EmployeeDao;
use Icc\Dao\RequestDao;
use Icc\Dao\UsedItemDao;
use Icc\Dao\WriteOffActDao;
use Icc\Database\DBConnector;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\WriteOffAct;
use Icc\PdfGenerator\MPDFGenerator;

class WriteOffController
{


    public static function generateWriteOffAct(int $id, array $items = array(), array $members = array()) {
        $pdf = MPDFGenerator::getInstance();
        $dao = new WriteOffActDao();
        $usedItemDao = new UsedItemDao();
        $employeeDao = new EmployeeDao();
        $actMembersDao = new ActMembersDao();

        try {
            $writeOffActInstance = $dao -> get($id);
//            $items = $usedItemDao -> where(array("date", "date"),
//                array("'" . $writeOffActInstance -> getStartDate() . "'", "'" . $writeOffActInstance -> getEndDate() . "'"), array(">=", "<="));
            $financiallyResponsible = $writeOffActInstance -> getResponsiblePersonEmployeeId();
            $connection = DBConnector::getInstance();
            $startDate = $writeOffActInstance -> getStartDate();
            $endDate = $writeOffActInstance -> getEndDate();
            $result = $connection -> execute_query("SELECT * FROM used_item INNER JOIN 
                stock_item ON item_id = stock_item.id WHERE ${financiallyResponsible} = stock_item.responsible_person_employee_id
                    AND used_item.date >= '${startDate}' AND used_item.date <= '${endDate}'");
            $result = $result -> fetch_all();
            $items = WriteOffAct::getItemsForWriteOffActTable($result);
            $members = [];
            $usualMembers = $actMembersDao -> where(array('act_position'), array('"Член Комісії"'), array('='));
            $deputyChairman = $actMembersDao -> where(array('act_position'), array('"Заст. гол. комісії"'), array('='))[0];
            $financiallyResponsibleEmployee = $employeeDao -> get($financiallyResponsible);
            $financiallyResponsibleMember = [];
            $financiallyResponsibleName = $financiallyResponsibleEmployee -> getSurname() . ' ' . $financiallyResponsibleEmployee -> getName() . ' ' . $financiallyResponsibleEmployee -> getPatronymic();
            array_push($financiallyResponsibleMember, $financiallyResponsibleName);
            array_push($financiallyResponsibleMember, $financiallyResponsibleEmployee -> getPosition());
            $head = $actMembersDao -> where(array('act_position'), array('"Голова Комісії"'), array('='))[0];
            $employee = $employeeDao -> get($head[1]);
            $employeeDeputyChairman = $employeeDao -> get($deputyChairman[1]);
            $head[1] = $employee -> getSurname() . ' ' . $employee -> getName() . ' ' . $employee -> getPatronymic();
            $deputyChairman[1] = $employeeDeputyChairman -> getSurname() . ' ' . $employeeDeputyChairman -> getName() . ' ' . $employeeDeputyChairman -> getPatronymic();
            array_push($head, $employee -> getPosition());
            array_push($deputyChairman, $employeeDeputyChairman -> getPosition());
            array_push($members, $head);
            array_push($members, $deputyChairman);
            array_push($members, $financiallyResponsibleMember);
            for ($i = 0; $i < count($usualMembers); $i++) {
                $employee = $employeeDao -> get($usualMembers[$i][1]);
                $usualMembers[$i][1] = $employee -> getSurname() . ' ' . $employee -> getName() . ' ' . $employee -> getPatronymic();
                array_push($usualMembers[$i], $employee -> getPosition());
                array_push($members, $usualMembers[$i]);
            }

            $months = array('січеня', 'лютого', 'березеня', 'квітня', 'травня', 'червня', 'липня', 'серпня', 'вересня', 'жовтня', 'листопада', 'грудня');
            $date = explode('-', $head[3]);
            $date[1] = $months[intval($date[1])];

//            array_push($members, $usualMembers[0]);
//            array_push($members, $usualMembers[1]);
//            array_push($members, $usualMembers[2]);
//            array_push($members, $usualMembers[2]);
            echo base64_encode($pdf -> writeOffAct($items, $members, $date));

        } catch (NotFoundItemException $e) {
            echo $e;
        }

    }

    public static function addNewWriteOffAct(string $startDate, string $endDate, int $responsible) {
        $dao = new WriteOffActDao();
        try {
            $startDateTimeInstance = new DateTime($startDate);
            $endDateTimeInstance = new DateTime($endDate);
            $writeOffAct = new WriteOffAct(-1, $startDateTimeInstance, $endDateTimeInstance, $responsible);
            $dao->save($writeOffAct);
        } catch (Exception | IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getAllWriteOffActs() {
        $dao = new WriteOffActDao();
        echo json_encode($dao -> convertArrayToModels($dao -> getAll()));
    }

    public static function deleteWriteOffAct($id) {
        $dao = new WriteOffActDao();
        try {
            $dao->delete(intval($id));
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function getWriteOffAct($id)
    {
        $dao = new WriteOffActDao();
        try {
            echo json_encode($dao->get($id));
        } catch (NotFoundItemException $e) {
        }
    }

    public static function updateWriteOffAct($id, $startDate, $endDate, $responsible)
    {
        $dao = new WriteOffActDao();
        try {
            $writeOffAct = new WriteOffAct($id, $startDate, $endDate, $responsible);
            $dao -> update($writeOffAct);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }


}