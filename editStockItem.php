<?php
include_once "StockController.php";
include_once "EmployeeController.php";

$ec = new EmployeeController();
$ar_far = EmployeeController::toArray($ec -> getAllResponsible());

if (isset($_GET['id'])) {
    $sc = new StockController();
//    echo 'value:' . intval($_GET['id']) . 'PPPP ' . PHP_EOL;
    $item = $sc -> getItemById(intval($_GET['id'])); //parsed array
}
print_r($_GET);
print_r($item);
?>
<head>
    <meta charset="UTF-8">
    <title>Insert title here</title>
    <script src="js/stock.js"></script>
</head>
<body onload="calculateTotal()">
<form method="post">
    <label>
        <input name="nameOfTheItem" type="text" value="<?php echo $item[1] ?>" required="required" placeholder="Name of the Item" autofocus="autofocus" autocomplete="off"><br/>
        <input name="typeOfTheItem" type="text" value="<?php echo $item[2] ?>" required="required" placeholder="Type of the Item" autocomplete="off"><br/>
        <input name="amount" type="number" value="<?php echo $item[3] ?>" required="required" placeholder="Amount" autocomplete="off"><br/>
        <input name="price" type="text" value="<?php echo $item[4] ?>" required="required" placeholder="Price" autocomplete="off"><br/> <!--- Add checking with js tricks --->
        <input name="total" type="text" value="<?php echo $item[5] ?>" placeholder="Total (not required)" autocomplete="off"><br/>
        <select name="responsible" required="required">
            <?php foreach ($ar_far as $arr) {
//                print_r($item);
//                echo $arr[0] . ' ' . $item[6] . PHP_EOL;
                if ($arr[0] == $item[6]) //Search selected initially item. Comparing id and stock item responsible_person_employee_id
                    echo "<option selected='selected' value=\"${arr[0]}\">${arr[1]} ${arr[2]} ${arr[3]} (Position: ${arr[4]}, Contact number: ${arr[5]})</option>";
                else
                    echo "<option value=\"${arr[0]}\">${arr[1]} ${arr[2]} ${arr[3]} (Position: ${arr[4]}, Contact number: ${arr[5]})</option>";

            } ?>
        </select><br/>
        <input name="code" type="text" value="<?php echo $item[7] ?>" placeholder="Code" required="required" autocomplete="off"><br/>
        <input type="submit" name="submitButton" value="Submit"><br/>
    </label>
</form>
</body>
