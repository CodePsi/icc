<?php

include_once "DBConnector.php";
include_once "Utils.php";
//class Stock {
//    private $id;
//    private $name;
//    private $type;
//    private $amount;
//    private $price;
//    private $total;
//    private $responsible_id;
//}

class StockController
{
    private $db;
    public function __construct()
    {
        $this -> db = new DBConnector();

    }

    public function addItem($data) {
//        $json_data = array_values($json_data);
        $value = $this -> db -> execute_query("INSERT INTO stock_item (id, item_name, type, amount, price, total, responsible_person_employee_id, code) VALUES (DEFAULT, '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]');");
        if (!$value) {
            echo DBConnector::$mysqli -> error;
//           header("Location: errorPage.html?error_text=${text}", true, 400);
        }
    }

    public function getAllStockItemsAsTable($sort_by=-1) {
        $values = $this -> db -> execute_query("SELECT * FROM stock_item");
        $values = Utils::toArray($values);
        for ($i = 0; $i < count($values); $i++) {
            array_push($values[$i], "<button name='edit' value='{$values[$i][0]}'>E</button>");
            array_push($values[$i], "<button name='delete' value='{$values[$i][0]}'>D</button>");
        }
        $data = null;
        if ($sort_by != -1) {
//            $data = Utils::to_html_table_array($this->sort_by($values, $sort_by), $header);
            $data = $this -> generate_html_table(Utils::sort_by($values, $sort_by));
        } else {
//            $data = Utils::to_html_table_array($values, $header, $style);
            $data = $this -> generate_html_table($values);
        }

        return $data;
    }

    public function getItemById($id) {
        $values = $this -> db -> execute_query("SELECT * FROM stock_item WHERE id=$id");
        return $values -> fetch_row();
    }

    private function generate_html_table(array $array) {
        $data = '<table class="table_request">';
        $header = array("ID", "Item name", "Type", "Amount", "Price", "Total", "Responsible person (ID)", "Inventory number (code)", "", ""); //Headers for table
        $data .= '<tr class="tr_request">';
//        echo $header[0];
        for ($i = 0; $i < count($array[0]); $i++) { //-2 because we have two buttons for editing and deleting of items.
            $data .= "<th>${header[$i]}</th>";
        }
        $data .= '</tr>';
        $data .= '<tr class="tr_request">';

        for ($i = 0; $i < count($array[0]); $i++) { //-2 because we have two buttons for editing and deleting of items.
            if ($i >= count($array[0]) - 2) $data .= "<td></td>"; //It is using for correct displaying of a table
            else
                $data .= "<td><button name='sort' value='$i'>Sort</button></td>";
        }
        $data .= '</tr>';
        foreach ($array as $arr) {
            $data .= '<tr class="tr_request">';
            for ($i = 0; $i < count($arr); $i++)
                $data .= "<td class='td_request'>$arr[$i]</td>";
            $data .= '</tr>';
        }
        $data .= '</table>';
        return $data;
    }

    public function getAllStockItems()
    {
        $values = $this -> db -> execute_query("SELECT * FROM stock_item");
        return Utils::toArray($values);
    }

    /**
     *
     * Add used items and inventory number of computer or machine where it was laid.
     *
     * @param $request_id int
     * @param $items array or ordered map. Template ["id": "amount"]
     * @param $inventory_numbers array
     */
    public function addUsedItem($request_id, $items, $inventory_numbers) {
        $now = date('Y-m-d H:i:s');
        $i = 0;
        if (!empty($items))
            foreach ($items as $id => $amount) {
                $this -> db -> execute_query("INSERT INTO used_item(id, request_id, item_id, amount, date, inventory_number) VALUES(DEFAULT, $request_id, $id, $amount, '$now', $inventory_numbers[$i])");
                $this -> db -> execute_query("UPDATE stock_item SET amount=amount-$amount WHERE id=$id");
                $i++;
            }
//        if (!empty($inventory_numbers))
//            foreach ($inventory_numbers as $item)
//                $this -> db -> execute_query("INSERT INTO inventory_number (id, request_id, inventory_number) VALUES (DEFAULT, $request_id, $item)");
        echo DBConnector::$mysqli -> error;

    }
}