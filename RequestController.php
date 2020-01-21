<?php
include_once "DBConnector.php";
include_once "Utils.php";
include_once "EmployeeController.php";

define('SORT_BUTTONS_HEADER', '<tr class="tr_request"><td><button name="sort" value="0">Sort</button></td><td><button name="sort" value="1">Sort</button></td><td><button name="sort" value="2">Sort</button></td><td><button name="sort" value="3">Sort</button></td><td><button name="sort" value="4">Sort</button></td><td><button name="sort" value="5">Sort</button></td></tr>');
/* @deprecated  */
define('SORT_BUTTONS_HEADER_SHORT', '<tr><td><button name="sort" value="0">Sort</button></td><td><button name="sort" value="2">Sort</button></td><td><button name="sort" value="3">Sort</button></td><td><button name="sort" value="4">Sort</button></td><td><button name="sort" value="5">Sort</button></td></tr>');
class RequestController
{
    private $db;
//    private $SORT_BUTTONS_HEADER = '<tr><td><button name="id_sort">Sort</button></td><td><button name="employee_id_sort">Sort</button></td><td><button name="building_sort">Sort</button></td><td><button name="auditorium_sort">Sort</button></td><td><button name="reason_sort">Sort</button></td><td><button name="date_sort">Sort</button></td></tr>';
    public function __construct()
    {
        $this -> db = new DBConnector();

    }

    /**
     * @param $data array
     */
    public function addRequest($data) {
//        $json_data = array_values($json_data);
//        $data[4] = date_format($data[4], 'U');
//        echo "INSERT INTO request (id, employee_id, building, auditorium, reason, date) VALUES (DEFAULT, '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]'";
        $value = $this -> db -> execute_query("INSERT INTO request (id, employee_id, building, auditorium, reason, date, status) VALUES (DEFAULT, '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]');");
        if (!$value) {
            echo DBConnector::$mysqli -> error;
        }
    }
    public function getRequestsAsTable($sort_by=-1) {
        $values = $this -> db -> execute_query("SELECT * FROM request");
        $values = Utils::toArray($values);
        $cope_values = $values;
        $ec = new EmployeeController();
        $requestsStatuses = array();
        for ($i =0; $i < count($values); $i++) {
            $e = $ec->getEmployeeById($values[$i][1]);
            $values[$i][1] = "${e[1]} ${e[2]} ${e[3]}";
            $requestsStatuses[$i] = $e[6];
        }
        var_dump($requestsStatuses);
        for ($i = 0; $i < count($values); $i++) {
//            $e = $ec->getEmployeeById($values[$i][1]);
            array_push($values[$i], "<button name='edit' value=\'" . $values[$i][0] . "\'>E</button>");
            if ($cope_values[$i][6] == 0) array_push($values[$i], "<button name='close_request' value=" . $values[$i][0] . ">C</button>");
            else array_push($values[$i], "OK");
            array_push($values[$i], "<button name='print' formtarget='_blank' rel='nofollow noopener noreferrer' value='" . $values[$i][0] . "'>P</button>");
//            array_push($values[$i], "<button id='slider-left' name='edit' value=\'" . $values[$i][0] . "\'>E</button>");
        }
        $data = null;
        if ($sort_by != -1) {
//            $data = Utils::to_html_table_array($this->sort_by($values, $sort_by), $header);

            $data = $this -> generate_html_table_for_request($this->sort_by($values, $sort_by));
        } else {
//            $data = Utils::to_html_table_array($values, $header, $style);
            $data = $this -> generate_html_table_for_request($values);
        }

        return $data;
    }

    public function getRequestById($id) {
        return $this -> db -> execute_query("SELECT * FROM request WHERE id=$id") -> fetch_row(); //Just getting only one row, for more information and more readable code see EmployeeController -> getEmployeeById method.
    }

    public function close_request($id) {
        $this -> db -> execute_query("UPDATE request SET status=1 WHERE id=$id");
    }

    private function sort_by($array, $sort_by) {
//        $array = Utils::toArray($array);
        $t = array_column($array, $sort_by);

        array_multisort($t, SORT_ASC, $array); //Yes, it is working somehow and I am not sure if it works right, but I want to sleep and I won't fix it today.
//        usort($array, function ($a, $b) { //It works too, but I don't think it a is good thing, uncomment it when you have a problem with sort, maybe it will help.
//            $a_val = $a[0];
//            $b_val = $b[0];
//            if ($a_val > $b_val) return 1;
//            if ($a_val < $b_val) return -1;
//            return 0;
//        });
//        print_r($array);
        return $array;
    }

    private function generate_html_table_for_request(array $array) {
        $data = '<table class="table_request">';
        $data .= SORT_BUTTONS_HEADER;
        foreach ($array as $arr) {
            $data .= '<tr class="td_request">';
            for ($i = 0; $i < count($arr); $i++)
                $data .= "<td class='td_request'>$arr[$i]</td>";
            $data .= '</tr>';
        }
        $data .= '</table>';
        return $data;
    }
}
//$rc = new RequestController();
//$rc -> getRequestsAsTable(1);