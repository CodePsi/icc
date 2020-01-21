<?php
include_once "RequestController.php";
include_once "EmployeeController.php";
include_once "StockController.php";
include_once "PDFGenerator.php";
$rc = new RequestController();
$sc = new StockController();
$items = $sc -> getAllStockItems();

$select = "<select name=SELECT_NAME>";
foreach ($items as $item)
    $select .= "<option value='$item[0]'>$item[1]</option>";
$select .= $body_select;
$select .= "</select><input name=INPUT_NAME type='text' style='width: 50px;' required='required'><br/>";
//print_r($_POST);
error_reporting (E_ALL ^ E_NOTICE);

if (isset($_POST['close_request'])) {
    header("location: closeRequest.php?request_id=" . $_POST['close_request']);
}

/* For working there shouldn't be any printing or output of the text. Because in case with edit button you won't be able to change header because there is already some text
In case with printing there's already text so PDF won't display properly.    */

//if (isset($_POST['print'])) {
//    $request = $rc -> getRequestById($_POST['print']);
//    $ec = new EmployeeController();
//    $employee = $ec -> getEmployeeById(1);
//    $unix_time = strtotime($request[5]);
//    MPDFGenerator::generateRequest($request[0], date('Y-m-d', $unix_time), date('h:i:s', $unix_time), $employee[1], $employee[6], $employee[5], $request[2], $request[3], "", $request[4]);
//}
if (isset($_POST['edit'])) {
    header("location: editRequest.php?id=" . str_replace("\'", "", $_POST['edit']));
}
//echo "123";
if (isset($_POST['sort'])) {
    $table = $rc -> getRequestsAsTable(intval($_POST['sort']));
} else {
    $table = $rc -> getRequestsAsTable(-1);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
        <link href="css/style.css" rel="stylesheet">
        <link href="pasha.css" rel="stylesheet">
        <script src="js/request.js"></script>
<!--        <script src="pasha.js"></script>-->
        <script>
            var items = 0;
            //Checking actual amount of item and passed in real time.
            function AddItem() {
                var div=document.getElementById("items");
                button=document.getElementById("add");
                var newItem = "<?php echo $select; ?>";
                newItem = newItem.replace('SELECT_NAME', 'sel' + items);
                newItem = newItem.replace('INPUT_NAME', 'in' + items);
                items++;
                var newNode=document.createElement("span");
                newNode.innerHTML=newItem;
                div.insertBefore(newNode,button);
            }
        </script>
    </head>
    <body onload="init()">
    <!--    <form method="POST">-->
    <!--        <label>-->
    <!--            <input type="checkbox" name="checkBox" onclick="onCheckButtonClick()" value="">-->
    <!--            <button style="display: none;" id="button">Button</button>-->
    <!--        </label>-->
    <!--    </form>-->

<!--    <button id="slider-left">left</button>-->
    <!--<div id="fon"></div>-->
    <!--<div id="slider"></div>-->
<!--    <input >-->
<!--    <button type="button"></button>-->
    <div id="background">
        <form method="POST">
                <?php echo $table; ?>
        </form>
    </div>
    </body>
</html>