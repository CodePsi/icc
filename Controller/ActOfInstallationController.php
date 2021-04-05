<?php

namespace Icc\Controller;

use DateTime;
use Exception;
use Icc\Dao\ActMembersDao;
use Icc\Dao\ActOfInstallationDao;
use Icc\Dao\EmployeeDao;
use Icc\Dao\UsedItemDao;
use Icc\Model\ActOfInstallation;
use Icc\Model\IncorrectObjectTypeException;
use Icc\Model\NotFoundItemException;
use Icc\Model\WriteOffAct;
use Icc\PdfGenerator\MPDFGenerator;

class ActOfInstallationController
{
    /**
     * Generate and encode act of installation in the pdf format.
     *
     * @param $id int id of act of installation
     * @see MPDFGenerator::actOfInstallation()
     */
    public static function generateActOfInstallation(int $id): void {
        $pdf = MPDFGenerator::getInstance();
        $actOfInstallationDao = new ActOfInstallationDao();
        $actMembersDao = new ActMembersDao();
        $employeeDao = new EmployeeDao();
        $usedItemDao = new UsedItemDao();

        $date = '';
        $head = '';
        $members = [];
        $financiallyResponsible = '';
        $items = [];
        try {

            $actOfInstallation = $actOfInstallationDao -> get($id);
            $items = $usedItemDao -> where(array("date", "date"),
                array("'" . $actOfInstallation -> getStartDate() . "'", "'" . $actOfInstallation -> getEndDate() . "'"), array(">=", "<="));

            $items = ActOfInstallation::getItemsForActOfInstallationTable($items);
            $date = ' ' . $actOfInstallation -> getMonth() . ' ' .explode('-', $actOfInstallation -> getStartDate())[0];
            $usualMembers = $actMembersDao -> where(array('act_position'), array('"Член Комісії"'), array('='));
            $financiallyResponsible = $actOfInstallation -> getResponsiblePersonEmployeeId();
            $financiallyResponsibleEmployee = $employeeDao -> get($financiallyResponsible);
            $financiallyResponsibleMember = [];
            $financiallyResponsibleName = $financiallyResponsibleEmployee -> getSurname() . ' ' . $financiallyResponsibleEmployee -> getName() . ' ' . $financiallyResponsibleEmployee -> getPatronymic();
            array_push($financiallyResponsibleMember, $financiallyResponsibleName);
            array_push($financiallyResponsibleMember, $financiallyResponsibleEmployee -> getPosition());
            $head = $actMembersDao -> where(array('act_position'), array('"Голова Комісії"'), array('='))[0];
            $employee = $employeeDao -> get($financiallyResponsible[1]);
            $financiallyResponsible[1] = $employee -> getPatronymic() . ' ' . $employee -> getName() . ' ' . $employee -> getSurname();
            $employee = $employeeDao -> get($head[1]);
            $head[1] = $employee -> getPatronymic() . ' ' . $employee -> getName() . ' ' . $employee -> getSurname();
            array_push($head, $employee -> getPosition());
            array_push($members, $financiallyResponsibleMember);
            for ($i = 0; $i < count($usualMembers); $i++) {
                $employee = $employeeDao -> get($usualMembers[$i][1]);
                $usualMembers[$i][1] = $employee -> getPatronymic() . ' ' . $employee -> getName() . ' ' . $employee -> getSurname();
                array_push($usualMembers[$i], $employee -> getPosition());
                array_push($members, $usualMembers[$i]);
            }
            echo base64_encode($pdf -> actOfInstallation($date, $financiallyResponsibleMember, $head[1], $members, $items));
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function addNewActOfInstallation(string $startDate, string $endDate, $month, $responsiblePersonEmployeeId) {
        $dao = new ActOfInstallationDao();
        try {
            $startDateTimeInstance = new DateTime($startDate);
            $endDateTimeInstance = new DateTime($endDate);
            $actOfInstallation = new ActOfInstallation(-1, $startDateTimeInstance, $endDateTimeInstance, $month, $responsiblePersonEmployeeId);
            $dao->save($actOfInstallation);
        } catch (Exception | IncorrectObjectTypeException $e) {
            echo $e;
        }
    }

    public static function getAllActsOfInstallation() {
        $dao = new ActOfInstallationDao();
        echo json_encode($dao->convertArrayToModels($dao -> getAll()));
    }

    public static function deleteActOfInstallation($id) {
        $dao = new ActOfInstallationDao();
        try {
            $dao->delete(intval($id));
        } catch (NotFoundItemException $e) {
            echo $e;
        }
    }

    public static function getActOfInstallation($id)
    {
        $dao = new ActOfInstallationDao();
        try {
            echo json_encode($dao -> get($id));
        } catch (NotFoundItemException $e) {
        }
    }

    public static function updateActOfInstallation($id, $startDate, $endDate, $month, $responsiblePersonEmployeeId)
    {
        $dao = new ActOfInstallationDao();
        try {
            $writeOffAct = new ActOfInstallation($id, $startDate, $endDate, $month, $responsiblePersonEmployeeId);
            $dao -> update($writeOffAct);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    }
}