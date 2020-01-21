<?php
include_once "DBConnector.php";

class Employee {
    private $id;
    private $name;
    private $surname;
    private $patronymic;
    private $status;
    private $contactNumber;
    private $position;
}
class EmployeeController
{
    private $db;
    public function __construct()
    {
        $this -> db = new DBConnector();
    }

    public function addEmployee($data) {
//        $data = array_values($data);
        $value = $this -> db -> execute_query("INSERT INTO employee (id, name, surname, patronymic, status, contactNumber, position) VALUES 
            (DEFAULT, '${data[0]}', '${data[1]}', '${data[2]}', '${data[3]}', '${data[4]}', '${data[5]}');");
        if (!$value) {
            echo DBConnector::$mysqli -> error;
//            header("HTTP/1.1 301");
//            header("Location: errorPage.html?error_text=${text}", true, 301);
        }
    }

    public function getEmployeesAsTable() {
        $values = $this -> db -> execute_query("SELECT * FROM employee");
        $data = '<table>';
        while ($array = $values -> fetch_row()) {
//            $json[$array[0]] = array();
//            $json[$array[0]][0] = count($array);
//            print_r($array);
//            echo "COUNT" . count($array) . PHP_EOL;
            $data .= '<tr>';
            for ($i = 1; $i < count($array); $i++)
                $data .= "<td>$array[$i]</td>";
            $data .= '</tr>';
        }
        $data .= '</table>';

        return $data;
    }

    public function getEmployeesAsArray() {
        $values = $this -> db -> execute_query("SELECT * FROM employee");
        $data = array();
        while ($array = $values -> fetch_row()) {
            array_push($data, $array);
        }

        return $data;
    }

    public function getEmployeeById($id) {
        $values = $this -> db -> execute_query("SELECT * FROM employee WHERE id=${id}");
        return $values -> fetch_row();
    }

    public function getChief() {
        $value = $this -> db -> execute_query("SELECT * FROM employee where status = 'Chief'");
        if (!$value) {
            echo DBConnector::$mysqli -> error;
        }

        return $value;
    }

    public function getAllResponsible() {
        $value = $this -> db -> execute_query("SELECT * FROM employee WHERE status = 'Local' OR status = 'Chief'");
        if (!$value) {
            echo DBConnector::$mysqli -> error;
        }

        return $value;
    }

    public static function toArray(mysqli_result $mysqli_result) {
        $data = array();
        while ($array = $mysqli_result -> fetch_row()) {
            array_push($data, $array);
        }
        return $data;
    }

//    public function getDB() {
//        return $this -> db;
//    }
}
//$ec = new EmployeeController();

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $data = json_decode(file_get_contents('php://input'), true);
//    $ec->addEmployee($data);
//}
//} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//    echo json_encode($ec -> getEmployees());
//}