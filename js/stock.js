// async function collectData() {
//
//     let valueNameOfTheItem = document.getElementById("nameOfTheItem").value;
//     let valueTypeOfTheItem = document.getElementById("typeOfTheItem").value;
//     let valueAmount = document.getElementById("amount").value;
//     let valuePrice = document.getElementById("price").value;
//     let valueTotal = document.getElementById("total").value;
//
//     if (valueTotal.toString().length === 0) {
//         valueTotal = valueAmount * valuePrice;
//     }
//
//     let response = await fetch("/icc/StockController.php", {
//         method: "POST",
//         headers: {
//             "Accept": "application/json",
//             "Content-Type": "application/json"
//         },
//         body: JSON.stringify({"name":valueNameOfTheItem,"type":valueTypeOfTheItem, "amount":valueAmount, "price":valuePrice, "total": valueTotal})
//     });
//     response.catch(function (err) {
//         console.log(err);
//     })
// }
var app = new Vue({
    el: '#app',
    data: {
        amount: 0,
        price: 0
    },
    computed: {
        totalSum: function () {
            return this.amount * this.price;
        }
    }
});
function init() {
    // calculateTotal();
    setUpResponsible();
}

// function calculateTotal() {
//     document.getElementsByName("amount")[0].addEventListener("keyup", function () {
//         document.getElementsByName('total')[0].value = document.getElementsByName('price')[0].value * document.getElementsByName('amount')[0].value;
//     });
//     document.getElementsByName("price")[0].addEventListener("keyup", function () {
//         document.getElementsByName('total')[0].value = document.getElementsByName('price')[0].value * document.getElementsByName('amount')[0].value;
//     })
// }

function setUpResponsible() {
    var request = new HttpRequest();
    request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
        if (request.isRequestSuccessful()) {
            var optionsRoot = document.getElementById('responsible');
            var jsonData = JSON.parse(request.xmlHttpRequestInstance.responseText);
            console.log(jsonData);
            console.log(jsonData[0]);
            for (var i = 0; i < jsonData[0].length; i++) {
                var option = document.createElement('option');
                option.value = jsonData[i][0];
                option.innerText = jsonData[i][1] + ' ' + jsonData[i][2] + ' ' + jsonData[i][3] + ', Position: ' + jsonData[i][4] + '; Contact Number: ' + jsonData[i][5];
                optionsRoot.appendChild(option);
            }
        }
    };
    request.sendPOSTRequest("/icc/employee/getAllEmployees", "");
}

// function toAddNewItemPage() {
//     window.location = "/icc/addStockItem.php";
// }

function addNewStockItem() {

}

function getAllStockItems() {
    var request = new HttpRequest();
    request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
        outputTable(JSON.parse(request.xmlHttpRequestInstance.responseText));
    };

    request.sendPOSTRequest("/icc/stock/getAllStockItems", "");
}

function editStockItem(id) {
    var request = new HttpRequest();
    var textNode = document.getElementById('addNewItemText');
    textNode.innerText = 'Edit Stock Item';
    // request.xmlHttpRequestInstance.setRequestHeader('x-filename', 'S');
    request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
        if (request.isRequestSuccessful()) {
            console.log(request.xmlHttpRequestInstance.responseText);
            var stockItemJson = JSON.parse(request.xmlHttpRequestInstance.responseText);
            var nameOfTheItem = document.getElementById('nameOfTheItem');
            var typeOfTheItem = document.getElementById('typeOfTheItem');
            var amount = document.getElementById('amount');
            var price = document.getElementById('price');
            var total = document.getElementById('total');
            var responsible = document.getElementById('responsible');
            var code = document.getElementById('code');
            nameOfTheItem.value = stockItemJson[1];
            typeOfTheItem.value = stockItemJson[2];
            amount.value = stockItemJson[3];
            price.value = stockItemJson[4];
            total.value = stockItemJson[5];
            responsible.value = stockItemJson[6];
            code.value = stockItemJson[7];
            document.getElementById('submitButton').onclick = function (ev1) {
                request.xmlHttpRequestInstance.onreadystatechange = function (ev2) {
                    if (request.isRequestSuccessful()) {
                        console.log(request.xmlHttpRequestInstance.responseText);
                    }
                };
                request.sendPUTRequest(""); //TODO Implement the Put request for updating item
            }
        }
    };
    request.sendPOSTRequest('/icc/stock/getStockItem', 'stockItemId=' + id);

}

function deleteStockItem(id) {

}
var tableButtons = [];

function outputTable(jsonParsedTable) {
    var oldTable = document.getElementById("stockItemsTable");
    if (oldTable) { oldTable.remove(); }
    var application = document.getElementsByClassName('main-container')[0];

    var table = buildTableFromJson(jsonParsedTable);
    table.id = "stockItemsTable";
    application.appendChild(table);
}

function clearButtonsFromUnnecessaryText() {
    for (var i = 0; i < tableButtons.length; i++) {

        tableButtons[i].innerText = "Sort";
    }
}

function buildTableFromJson(jsonData) {
    var table = document.createElement('table');
    var tr1 = table.insertRow();
    if (tableButtons.length > 0) {
        for (var i = 0; i < tableButtons.length; i++) {
            var td1 = tr1.insertCell();
            td1.appendChild(tableButtons[i]);
        }

    } else {
        for (let i = 0; i < jsonData[i].length; i++) {
            var td = tr1.insertCell();
            var button = document.createElement('button');
            tableButtons.push(button);
            button.innerText = "Sort";
            button.onclick = function (ev) {
                if (this.innerText === "Sort ˅") {
                    clearButtonsFromUnnecessaryText();
                    this.innerText = "Sort ^";
                    jsonData.sort(function (a, b) {
                        if (a[i] === b[i]) return 0; else return (a[i] > b[i]) ? -1 : 1;
                    });
                    outputTable(jsonData);

                } else if (this.innerText === "Sort ^") {
                    this.innerText = "Sort";
                    jsonData.sort(function (a, b) {
                        if (a[0] === b[0]) return 0; else return (a[0] > b[0]) ? -1 : 1;
                    });
                    outputTable(jsonData);
                } else {
                    clearButtonsFromUnnecessaryText();
                    this.innerText = "Sort ˅";
                    console.log(jsonData);
                    jsonData.sort(function (a, b) {
                        if (a[i] === b[i]) return 0; else return (a[i] < b[i]) ? -1 : 1;
                    });
                    outputTable(jsonData);
                }
            };

            button.value = i;
            td.appendChild(button);
        }
    }
    //Insert two cells for more good displaying
    // tr1.insertCell(8);
    // tr1.insertCell(9);
    for (let i = 0; i < jsonData.length; i++) {
        var tr = table.insertRow();
        for (var j = 0; j < jsonData[i].length; j++) {
            td = tr.insertCell();

            td.appendChild(document.createTextNode(jsonData[i][j]));
        }

        var tdEdit = tr.insertCell();
        // var tdClose = tr.insertCell();
        var tdDelete = tr.insertCell();
        var buttonEdit = document.createElement('button');
        // var buttonClose = document.createElement('button');
        var buttonDelete = document.createElement('button');
        buttonEdit.innerText = "E";
        // buttonClose.innerText = "C";
        buttonDelete.innerText = "D";
        buttonEdit.value = jsonData[i][0x0];
        buttonDelete.value = jsonData[i][0];
        // buttonClose.value = jsonData[i][0];
        buttonEdit.className = "standard-button";
        // buttonClose.className = "standard-button";
        buttonDelete.className = "standard-button";
        buttonEdit.onclick = function (ev) { editStockItem(this.value); };
        // buttonClose.onclick = function (ev) { close };
        buttonDelete.onclick = function (ev) {
            //deleteStockItem(this.value);
            smoothScroll();
        };
        tdEdit.appendChild(buttonEdit);
        // tdClose.appendChild(buttonClose);
        tdDelete.appendChild(buttonDelete);
    }

    return table;
}

function sortBy(by) {

}

function smoothScroll() {
    var currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
    if (currentScroll > 0) {
        window.requestAnimationFrame(smoothScroll);
        window.scrollTo (0,currentScroll - (currentScroll/5));
    }
}