<?php


namespace Icc\Controller;


use Icc\Dao\EmployeeDao;
use Icc\Model\Employee;
use Icc\Model\IncorrectObjectTypeException;

class EmployeeController
{
    public static function addNewEmployee($name, $surname, $patronymic, $status, $contactNumber, $position, $responsible) {
        $dao = new EmployeeDao();
        $entity = new Employee(-1, $name, $surname, $patronymic, $status, $contactNumber, $position, $responsible);
        try {
            $dao -> save($entity);
        } catch (IncorrectObjectTypeException $e) {
            echo $e;
        }
    } 

    public static function getAllEmployees() {
        $dao = new EmployeeDao();
        echo json_encode($dao -> getAll());
    }
}