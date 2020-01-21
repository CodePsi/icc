<?php
include "EmployeeController.php";
$ec = new EmployeeController();
$data = $ec -> getEmployeesAsTable();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="js/employee.js"></script>
</head>
    <body>
        <button href="/icc/addNewEmployee.php">Add New Employee</button>
        <form method="POST">
            <?php echo $data; ?>
        </form>
    </body>
</html>