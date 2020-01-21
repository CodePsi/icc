<?php
include_once "EmployeeController.php";
include_once "StockController.php";

$ec = new EmployeeController();
$ar_far = EmployeeController::toArray($ec -> getAllResponsible());

if ($_POST['submitButton']) {
    $sc = new StockController();
    $sc -> addItem(array($_POST['nameOfTheItem'], $_POST['typeOfTheItem'], $_POST['amount'], $_POST['price'], $_POST['total'], $_POST['responsible'], $_POST['code']));
//    header('location: stockControlPage.php', false, 201);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert title here</title>
    <script src="js/stock.js"></script>
</head>
<body onload="calculateTotal()">
    <form method="post">
        <label>
            <input name="nameOfTheItem" type="text" required="required" placeholder="Name of the Item" autofocus="autofocus" autocomplete="off"><br/>
            <input name="typeOfTheItem" type="text" required="required" placeholder="Type of the Item" autocomplete="off"><br/>
            <input name="amount" type="number" required="required" placeholder="Amount" autocomplete="off"><br/>
            <input name="price" type="text" required="required" placeholder="Price" autocomplete="off"><br/> <!--- Add checking with js tricks --->
            <input name="total" type="text" placeholder="Total (not required)" autocomplete="off"><br/>
            <select name="responsible" required="required">
                <?php foreach ($ar_far as $arr)
//                    if ($arr[0] == $item[6])
                    echo "<option value=\"${arr[0]}\">${arr[1]} ${arr[2]} ${arr[3]} (Position: ${arr[4]}, Contact number: ${arr[5]})</option>";
//                 else
//                    echo "<option selected=\"selected\" value=\"${arr[0]}\">${arr[1]} ${arr[2]} ${arr[3]} (Position: ${arr[4]}, Contact number: ${arr[5]})</option>";
                ?>
            </select><br/>
            <input name="code" type="text" placeholder="Code" required="required" autocomplete="off"><br/>
            <input type="submit" name="submitButton" value="Submit"><br/>
        </label>
    </form>
</body>
</html>