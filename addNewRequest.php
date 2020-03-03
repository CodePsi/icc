<?php
include "EmployeeController.php";
include "RequestController.php";
include "Utils.php";
$ec = new EmployeeController();
$array = $ec -> getEmployeesAsArray();
$chief_far = $ec -> getChief() -> fetch_row();
$time = null;
if (isset($_POST['submit'])) {
//    echo "<script>console.log('test');</script>";
//    print_r(Utils::get_post_parameters(array("submit")));
    $sc = new RequestController();

    $sc -> addRequest(array($_POST['employee'], $_POST['building'], $_POST['auditorium'], $_POST['cause'], date('Y-m-d H:i:s', $time)));
}

//foreach ($array as $item)
//    print_r($item);
//print_r($array);
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
        <input type="datetime-local" name="requestTime" value="<?php echo date('Y-m-d\Th:i:s', $time = time()); ?>">
    <br/>

        <select name="employee">

            <?php foreach ($array as $arr) {
                echo "<option value=\"${arr[0]}\">${arr[1]} ${arr[2]} ${arr[3]} (Position: ${arr[4]}, Contact number: ${arr[5]})</option>";
            }
            ?>
        </select><br/>
        <select name="building">
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
    <br/>
    <input type="text" name="auditorium" placeholder="Auditorium"><br/>
    <input type="text" name="cause" placeholder="The cause for the machine failure"><br/>
    <input type="text" name="chief" placeholder="Chief" value="<?php echo $chief_far[1] . ' ' . $chief_far[2][0] . '. ' . $chief_far[3][0] . '. '; ?>" readonly="readonly"><br/>
    <button name="submit">Add</button>
    </label>
</form>
</body>
</html>
