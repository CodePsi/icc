<!--ALL OF IT ARE FUCKING SHIT BECAUSE OF OLD AND STUPID FUCKED UP THING-->
<?php
include "EmployeeController.php";
if (isset($_POST['submit'])) {
    $data = array();
    array_push($data, $_POST['nameOfTheEmployee'], $_POST['surnameOfTheEmployee'], $_POST['patronymicOfTheEmployee'], $_POST['status'], $_POST['contactNumber'], $_POST['position']);
    $ec = new EmployeeController();
    $ec -> addEmployee($data);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="js/employee.js"></script>
</head>
<body>
    <form method="POST">
        <label>
            <input name="nameOfTheEmployee" type="text" required="required" placeholder="Name" autofocus="autofocus"><br/>
            <input name="surnameOfTheEmployee" type="text" required="required" placeholder="Surname"><br/>
            <input name="patronymicOfTheEmployee" type="text" required="required" placeholder="Patronymic"><br/>
            <select name="status">
                <option selected="selected">Local</option>
                <option>Global</option>
                <option onclick="selectedChief()">Chief</option>
            </select>
            <input name="contactNumber" type="text" required="required" placeholder="Contact Number"><br/>
            <input name="position" type="text" required="required" placeholder="Position"><br/>
            <input type="submit" name="submit">
        </label>
    </form>
</body>
</html>

