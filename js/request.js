// register modal component
Vue.component('modal', {
    template: '#modal-template',
    props: {
        width: String
    },
    methods: {
        getActiveID: function() {
            return app.activeModalValue
        }
    }
});

var componentGrid = Vue.component('grid', {
    template: '#grid-template',
    props: {
        heroes: Array,
        columns: Array,
        filterKey: String
    },
    data: function () {
        var sortOrders = {};
        this.columns.forEach(function (key) {
            sortOrders[key] = 1
        });
        console.log('TEST:' + this.columns);
        return {
            sortKey: '',
            sortOrders: sortOrders,
            hers: this.heroes
        }
    },
    computed: {
        filteredHeroes: function () {
            var sortKey = this.sortKey;
            var filterKey = this.filterKey && this.filterKey.toLowerCase();
            var order = this.sortOrders[sortKey] || 1;
            var heroes = this.heroes;
            if (filterKey) {
                heroes = heroes.filter(function (row) {
                    return Object.keys(row).some(function (key) {
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1;
                    })
                })
            }
            if (sortKey) {
                heroes = heroes.slice().sort(function (a, b) {
                    a = a[sortKey];
                    b = b[sortKey];
                    return (a === b ? 0 : a > b ? 1 : -1) * order;
                })
            }
            return heroes
        }
    },
    filters: {
        capitalize: function (str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    },
    methods: {
        sortBy: function (key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        displayCloseRequestModalWindow: function (value) {
            app.activeModalValue = value;
            app.showCloseEntryModal = true;
            app.updateAllStockItemsEntries();
            app.usedItems = [];
            app.inventoryNumbers = [];
        },
        displayEditRequestModalWindow: function (value) {
            app.activeModalValue = value;
            app.showEditEntryModal = true;
            var request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
                if (request.isRequestSuccessful()) {
                    var requestInstance = JSON.parse(request.xmlHttpRequestInstance.responseText);
                    app.employee = requestInstance['employeeId'];
                    app.building = requestInstance['building'];
                    app.auditorium = requestInstance['auditorium'];
                    app.cause = requestInstance['reason'];
                    app.status = requestInstance['status'];
                    var tempDate = requestInstance['date'].split(' ');
                    app.date = tempDate[0] + 'T' + tempDate[1];
                }
            };

            request.sendGETRequest('/icc/requests/getRequest?id=' + value, '');
        },
        printRequest: function (value) {
            window.open('requestPdf?id=' + value, '_blank');
        }
    }
});


// start app
var app = new Vue({
    el: '#app',
    data: {
        showModal: false,
        showNewEntryModal: false,
        showPrintModal: false,
        showEditEntryModal: false,
        showCloseEntryModal: false,
        activeModalValue: -1,
        searchQuery: '',
        gridColumns: ['id', 'employee Id', 'building', 'auditorium', 'reason', 'date', 'status'],
        gridData: [],
        employees: [],
        date: getNow(),
        employee: '',
        building: 'Main',
        auditorium: '',
        cause: '',
        status: '',
        textBoxes: [],
        selects: [],
        usedItems: [],
        inventoryNumbers: [],
        stockItemEntries: []
    },
    computed: {
        chief: function () {
            this.getAllEmployees(); //For updating app.employees variable

            for (var i = 0; i < app.employees.length; i++) {
                if (app.employees[i]['status'] === 'Chief') {
                    return app.employees[i]['name'] + ' ' + app.employees[i]['surname'];
                }
            }

            return 'None';
        }

    },
    methods: {
        getAllEmployees: function () {
            var request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
                if (request.isRequestSuccessful()) {
                    var jsonData = JSON.parse(request.xmlHttpRequestInstance.responseText);
                    for (var i = 0; i < jsonData.length; i++) {
                        console.log(i + ';' + jsonData[i][0]);
                        app.employees[i] = {
                            'id': jsonData[i][0],
                            'name': jsonData[i][1],
                            'surname': jsonData[i][2],
                            'patronymic': jsonData[i][3],
                            'status': jsonData[i][4],
                            'contactNumber': jsonData[i][5],
                            'position': jsonData[i][6],
                            'responsible': jsonData[i][7]
                        };
                    }
                }
            };
            request.sendPOSTRequest("/icc/employee/getAllEmployees", "");
        },
        updateAllStockItemsEntries: function() {
            var request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
                if (request.isRequestSuccessful()) {
                    var stockItemsJSON = JSON.parse(request.xmlHttpRequestInstance.responseText);
                    for (var i = 0; i < stockItemsJSON.length; i++) {
                        app.stockItemEntries[i] = {
                            'id': stockItemsJSON[i][0],
                            'itemName': stockItemsJSON[i][1],
                            'type': stockItemsJSON[i][2],
                            'amount': stockItemsJSON[i][3],
                            'price': stockItemsJSON[i][4],
                            'total': stockItemsJSON[i][5],
                            'responsiblePerson': stockItemsJSON[i][6],
                            'code': stockItemsJSON[i][7]
                        };
                    }
                }
            };
            request.sendGETRequest("/icc/stock/getAllStockItems", "");
        },
        closeNewEntryModalWindow: function () {
            console.log(this.date + ' ' + this.employee + ' ' + this.building + ' ' + this.auditorium + ' ' + this.cause + ' ' + this.chief);
            this.showNewEntryModal = false;
            var request = new HttpRequest();
            request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
                if (request.isRequestSuccessful()) {
                    console.log('Status: ' + request.xmlHttpRequestInstance.responseText);
                    getAllRequests();
                }
            };

            request.sendPOSTRequest("/icc/requests/addRequest", JSON.stringify({'date': this.date, 'employee': this.employee, 'building': this.building, 'auditorium': this.auditorium, 'cause': this.cause, 'status': 0}));
        },
        openNewEntryRequestWindow: function() {
            this.date = getNow();

            this.showNewEntryModal = true;
        },
        closePrintModalWindow: function () {

        },
        updateRequest: function (value) {
            var request = new HttpRequest();
            this.showEditEntryModal = false;
            request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
                if (request.isRequestSuccessful()) {
                    console.log('Status: ' + request.xmlHttpRequestInstance.responseText);

                    getAllRequests();
                }
            };

            request.sendPATCHRequest("/icc/requests/updateRequest", JSON.stringify({'id': this.activeModalValue, 'date': this.date, 'employee': this.employee, 'building': this.building, 'auditorium': this.auditorium, 'cause': this.cause, 'status': this.status}));
        },
        closeRequest: function() {
            var request = new HttpRequest();
            var data = {"usedItems": [], "inventoryNumbers": [], "requestId": this.activeModalValue};
            console.log(app.inventoryNumbers);
            for (let i = 0; i < app.inventoryNumbers.length; i++) {
                data["inventoryNumbers"].push(this.$refs[this.inventoryNumbers[i]['inventoryNumber']][0].value);
            }
            for (let i = 0; i < app.usedItems.length; i++) {
                var select = this.$refs[this.usedItems[i]['option']][0].value;
                var input = this.$refs[this.usedItems[i]['input']][0].value;
                data["usedItems"].push({ 'select': select, 'input': input });
            }

            request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
                if (request.isRequestSuccessful()) {
                    console.log(request.xmlHttpRequestInstance.responseText);
                }
            };

            request.sendPOSTRequest("/icc/requests/closeRequest", JSON.stringify(data));
            // console.log(data);

        },
        test123: function() {
            this.gridData.push({id: '', 'employee Id': '', building: '',
                auditorium: '', reason: '', date: '',
                status: ''});
        },
        addInputBox: function () {
            this.usedItems.push({'option': 'opt' + this.usedItems.length, 'input': 'in' + this.usedItems.length});
        },
        addInventoryNumberField: function () {
            this.inventoryNumbers.push({'inventoryNumber': 'invnum' + this.inventoryNumbers.length});
        }
    }
});

function inst() {
    var request = new HttpRequest();
    request.sendGETRequest("/icc/requestPanel/getAllRequests", "");
    request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
        if (request.isRequestSuccessful()) {
            // var parsedData = JSON.parse(request.xmlHttpRequestInstance.responseText);
            // console.log('test');
            app.gridData = [
                { name: 'Chuck Norris', power: Infinity },
                { name: 'Bruce Lee', power: 9000 },
                { name: 'Jackie Chan', power: 7000 },
                { name: 'Jet Li', power: 8000 }
            ];
        }
    };
}

function onCheckButtonClick() {
    document.getElementById('button').click();
}

function loadElements() {
    // alert('added');
    document.getElementsByTagName('body')[0].innerHTML += '    <div id="prizrak"><button id="slider-right" onclick="closeForm()">закрыть</button>\n' +
        '        <form method="POST">\n' +
        '            <label>\n' +
        '                <div id="items" class="items">\n' +
        '                    <input type="button" value="Добавить поле+" onClick="AddItem();" ID="add">' +
        '                </div>\n' +
        '                <div id="inventory_numbers" class="items">\n' +
        '                    <br/><input type="button" onclick="addInventoryNumberField()" id="add_inventory" value="Add new field for inventory number">\n' +
        '                </div>\n' +
        '                <input name="submit" class="btn" type="submit" value="Отправить" />\n' +
        '            </label>\n' +
        '        </form>\n' +
        '    </div>';
}
function getNow() {
    var d = new Date();
    console.log(d.getFullYear() + '-' + fixDate(d.getMonth()) + '-' + fixDate(d.getDate()) + '\T' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds());
    return d.getFullYear() + '-' + fixDate(d.getMonth()) + '-' + fixDate(d.getDate()) + '\T' + fixDate(d.getHours()) + ':' + fixDate(d.getMinutes()) + ':' + fixDate(d.getSeconds());
}
function fixDate(date) {
    if (date < 10) {
        return '0' + date;
    } else {
        return date;
    }
}
var inventory_items = 0;
function addInventoryNumberField() {
    var parent = document.getElementById("inventory_numbers");
    var button = document.getElementById("addInventoryNumberButton");
    var newItem = "<input type='text' name='invnum" + inventory_items + "' placeholder='Inventory number'><br\>";
    inventory_items++;
    var newNode=document.createElement("span");
    newNode.innerHTML=newItem;
    parent.insertBefore(newNode, button);
}



function closeForm() {
    var element = document.querySelector("#prizrak");
    element.remove();

}

function init() {
    // document.getElementById('slider-left').onclick = sliderLeft;
    // loadElements();
    getAllRequests();
    app.getAllEmployees();
    app.updateAllStockItemsEntries();
    // console.log(componentGrid.heroes);
    // app.chief = app.getChief();
}
function sliderLeft() {
    loadElements();
    var polosa2 = document.getElementById('prizrak');
    var g = 1;
    // var polosa5 = document.getElementById('fon');
    // polosa5.style.zIndex = "18";
    // polosa5.style.opacity = "0.8";
    // polosa5.style.height = 100 + "vh";
    polosa2.style.opacity = "1";
    polosa2.style.zIndex = "20";
}

function initCloseRequestPage() {
    var buttonForInventoryNumbers = document.getElementById("addInventoryNumberButton");
    // var buttonForStockItems = document.getElementById("addNewStockItemButton");
    buttonForInventoryNumbers.onclick = addInventoryNumberField;
    // buttonForStockItems.onclick = addStockItemField;
}

function getAllRequests() {
    var request = new HttpRequest();
        request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
            if (request.isRequestSuccessful()) {
                //Remove old table if it exists
                // var oldTable = document.getElementById('outputTable');
                // if (oldTable) {
                //     oldTable.remove();
                // }
                // console.log(request.xmlHttpRequestInstance.responseText);
                var parsedData = JSON.parse(request.xmlHttpRequestInstance.responseText);
                for (var i = 0; i < parsedData.length; i++) {
                        parsedData[i] = {id: parsedData[i][0], 'employee Id': parsedData[i][1], building: parsedData[i][2],
                        auditorium: parsedData[i][3], reason: parsedData[i][4], date: parsedData[i][5],
                        status: parsedData[i][6]};
                }
                app.gridData = parsedData;
                // app.gridData = [{name: "123",  power: "asd"}];
                // outputTable(parsedData);
                // app.gridData = [
                //     {}
                // ];

                // console.log(app.gridData);
                // console.log(parsedData);
                // var app = document.getElementsByClassName('main-container')[0];
                // var table = buildTableFromJson(parsedData);
                // table.id = "outputTable";
                //
                // app.appendChild(table);
            }
        };
        request.sendGETRequest("/icc/requestPanel/getAllRequests", "");
    // inst();
}

var tableButtons = [];

function outputTable(jsonParsedTable) {
    var oldTable = document.getElementById("requestTable");
    if (oldTable) { oldTable.remove(); }
    var application = document.getElementsByClassName('main-container')[0];

    var table = buildTableFromJson(jsonParsedTable);
    table.id = "requestTable";
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
        console.log(jsonData[0].length);
        for (let i = 0; i < jsonData[0].length; i++) {
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
    for (let i = 0; i < jsonData.length; i++) {
        var tr = table.insertRow();
        for (var j = 0; j < jsonData[i].length; j++) {
            td = tr.insertCell();

            td.appendChild(document.createTextNode(jsonData[i][j]));
        }

        var tdEdit = tr.insertCell();
        var tdClose = tr.insertCell();
        var tdDelete = tr.insertCell();
        var buttonEdit = document.createElement('button');
        var buttonClose = document.createElement('button');
        var buttonDelete = document.createElement('button');
        buttonEdit.innerText = "E";
        buttonClose.innerText = "C";
        buttonDelete.innerText = "D";
        buttonEdit.value = jsonData[i][0x0];
        buttonDelete.value = jsonData[i][0];
        buttonClose.value = jsonData[i][0];
        buttonEdit.className = "standard-button";
        buttonClose.className = "standard-button";
        buttonDelete.className = "standard-button";
        buttonEdit.onclick = function (ev) { editRequest(this.value); };
        // buttonClose.onclick = function (ev) { app.showModal = true; console.log(app.showModal) };
        buttonDelete.onclick = function (ev) { deleteRequest(this.value); };
        tdEdit.appendChild(buttonEdit);
        // tdClose.appendChild(buttonClose);
        tdClose.innerHTML = '<button @click="showModal = true">Show Modal</button>';
        tdDelete.appendChild(buttonDelete);
    }

    return table;
}