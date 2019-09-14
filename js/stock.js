async function collectData() {

    let valueNameOfTheItem = document.getElementById("nameOfTheItem");
    let valueTypeOfTheItem = document.getElementById("typeOfTheItem");
    let valueAmount = document.getElementById("amount");
    let valuePrice = document.getElementById("price");
    let valueTotal = document.getElementById("total");

    let response = await fetch("/icc/StockController.php", {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({"name":valueNameOfTheItem,"type":valueTypeOfTheItem, "amount":valueAmount, "price":valuePrice, "total": valueTotal})
    });
}