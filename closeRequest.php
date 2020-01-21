<?php
include_once "StockController.php";
include_once "RequestController.php";
include_once "StringUtils.php";

$sc = new StockController();
if (isset($_POST['cancel'])) {
    header("location: requestControlPage.php");
}
error_reporting (E_ALL ^ E_NOTICE);
if (isset($_POST['close_request'])) {
    $rc = new RequestController();
    $i = 0;
    $sel_value = '';
    $items = array();
    $inventory_numbers = array();
    //It works in a pretty strange and unusual way. So, for the first, we need just get the "sel" value and then the "in" always goes next.
    foreach ($_POST as $item => $value) {
        if (StringUtils::startsWith("sel", $item)) {
            $sel_value = $value;
        } else if (StringUtils::startsWith("invnum", $item)) {
            array_push($inventory_numbers, $value);
        } else if (StringUtils::startsWith("in", $item)) {
            $items[$sel_value] = $value;
        }
    }
//    var_dump($inventory_numbers);
//    var_dump($items);
    $sc -> addUsedItem(intval($_GET['request_id']), $items, $inventory_numbers);
    $rc -> close_request(intval($_GET['request_id']));
    echo DBConnector::$mysqli -> error;
    //    header("location: requestControlPage.php");
}


$array_max_values_js = "[";
$items = $sc -> getAllStockItems();
$select = "<select name=SELECT_NAME>";
$default_max_value = -1;
foreach ($items as $item) {
    $select .= "<option value='$item[0]'>$item[1]</option>";
    $array_max_values_js .= $item[3] . ',';
    if ($default_max_value === -1) $default_max_value = $item[3];
}
$select .= "</select><input name=INPUT_NAME type='number' style='width: 50px;' max='$default_max_value' required='required'><br/>";
//$select .= "";
$array_max_values_js[strlen($array_max_values_js) - 1] = ']'; //Because there're always comma at the end of the sequence which we don't need.
//echo $array_max_values_js;
?>

<html lang="en">
    <head>
        <title>Closing of Request</title>
        <script src="js/request.js"></script>
        <script>
            var items = 0;
            var max_items = <? echo $array_max_values_js ?>;
            //Checking actual amount of item and passed in real time.
            function addStockItem() {
                var div = document.getElementById("stock_items");
                var button = document.getElementById("addNewStockItemButton");
                var newItem = "<?php echo $select; ?>";
                newItem = newItem.replace('SELECT_NAME', 'sel' + items);
                newItem = newItem.replace('INPUT_NAME', 'in' + items);
                var newNode=document.createElement("span");
                newItem += "<input type='text' name='invnum" + items + "' placeholder='Inventory number'><br\>";
                newNode.innerHTML=newItem;
                div.insertBefore(newNode,button);
                // console.log('sel' + items);
                document.getElementsByName('sel' + items)[0].onchange = function (ev) {
                    var select = ev.currentTarget;
                    var name = select.name;
                    document.getElementsByName("in" + name[name.length - 1])[0].max = max_items[select.options.selectedIndex]; //Get the last number from the current target's name then take related to this select element the input element and set max value to it according to its amount at the database
                }; //Stupid IDE shows that there's no such variable like options, but actually there's exist a variable.
                items++;
            }
        </script>
    </head>

    <body onload="initCloseRequestPage()">
        <form name="mainForm" method="POST">
            <div id="stock_items">
                <button type="button" id="addNewStockItemButton" onclick="addStockItem()">Add new field</button>
            </div>
            <br/>
            <div id="inventory_numbers">
<!--                <button type="button" id="addInventoryNumberButton">Add new field for an inventory number</button>-->
            </div>

            <button name="cancel">Cancel</button><br/>
            <button name="close_request">Close</button>
        </form>
    </body>
</html>