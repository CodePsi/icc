<?php
include_once "StockController.php";
include_once "Utils.php";
$sc = new StockController();


Utils::on_post_request_redirect("addNewItem", "addStockItem.php");

if (isset($_POST['sort']))
    $table = $sc -> getAllStockItemsAsTable(intval($_POST['sort']));
else
    $table = $sc -> getAllStockItemsAsTable(-1);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Control Page For Stock</title>
        <link rel="stylesheet" href="css/style.css">
        <script src="js/stock.js" type="application/javascript"></script>
    </head>
    <body>
        <div class="header-container">
            <form method="post">
                <button name="addNewItem" href="/icc/addStockItem.php">Add New Item</button>
            </form>
        </div>
    <div class="main-container">
            <form method="post">
                    <?php echo $table; ?>
            </form>
        </div>
    </body>
</html>
