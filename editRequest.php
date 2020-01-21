<?php
include_once "RequestController.php";
include_once "EmployeeController.php";
//print_r($_GET);
$ec = new EmployeeController();
$array = $ec -> getEmployeesAsArray();
$chief_far = $ec -> getChief() -> fetch_row();
if (isset($_GET['id'])) {
    $rc = new RequestController();
    $request = $rc -> getRequestById(intval($_GET['id'])); //TODO Prevent null values in request.
//    print_r($request);
//    echo "TTT" . $request[5];
//    echo strtotime($request[5]);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert title here</title>
    <!--    <script src="../js/requests.js" type="text/javascript"></script>-->
</head>
<body>
<form method="post">
    <label>
        <input type="datetime-local" name="requestTime" value="<?php echo date('Y-m-d\Th:i:s', strtotime($request[5])); ?>">
        <br/>

        <select name="employee">

            <?php foreach ($array as $arr) {
                if ($arr[0] == $request[1])
                    echo "<option selected='selected' value=\"${arr[0]}\">${arr[1]} ${arr[2]} ${arr[3]} (Position: ${arr[4]}, Contact number: ${arr[5]})</option>";
                else
                    echo "<option value=\"${arr[0]}\">${arr[1]} ${arr[2]} ${arr[3]} (Position: ${arr[4]}, Contact number: ${arr[5]})</option>";
            }
            ?>
        </select><br/>

        <select id="select" name="building">
            <option disabled="disabled">Buildings</option>
            <option>Main</option>
            <option>Humanitarian</option>
            <option>Preschool and technological education (PTD or DTO)</option>
            <option>Graphic art</option>
            <option>Musical</option>
            <option disabled="disabled">Dormitories</option>
            <option>Dormitory #1</option>
            <option>Dormitory #2</option>
            <option>Dormitory #3</option>
        </select>

        <?php echo "<script>
            var freaky = '$request[2]';
            var arr = document.getElementById('select').options;
            for(var j = 0; j < arr.length; j++) {
                if (arr[j].text === freaky)
                    arr.selectedIndex = j;
            }         
        </script>"; //This part is freaky and I know this, but by virtue of old computers, I must do this in this way. It can be replaced with adequate fetch in js and JSON-data in PHP.?>
        <br/>
        <input type="text" name="auditorium" value="<?php echo $request[3]; ?>" placeholder="Auditorium"><br/>
        <input type="text" name="cause" value="<?php echo $request[4]; ?>" placeholder="The cause for the machine failure"><br/>
        <input type="text" name="chief" placeholder="Chief" value="<?php echo $chief_far[1] . ' ' . $chief_far[2][0] . '. ' . $chief_far[3][0] . '. '; ?>" readonly="readonly"><br/>
        <button name="submit">Add</button>
    </label>
</form>
</body>
</html>

