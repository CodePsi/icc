// importScripts('TableBuilder.js');

var app = new Vue({
    el: '#app',
    data: {
        message: 'Hello Vue!',
        startDate: '1950-01-01',
        endDate: '1950-01-01',
        pdf: ''
    }
});

function f() {
    var xmlHttp = new XMLHttpRequest();   // new HttpRequest instance
    var theUrl = "/icc/WriteOffController.php";
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState === 4) { // Request is done
            if (xmlHttp.status === 200) { // successfully
                alert(xmlHttp.responseText); // We're calling our method
            }
        }
    };
    xmlHttp.open("POST", theUrl, true);
    xmlHttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xmlHttp.send(false);
}

function generateWriteOffAct(id) {
    var d = new HttpRequest();
    // d.xmlHttpRequestInstance.setRequestHeader('Content-type', 'plain/text');
    d.xmlHttpRequestInstance.onreadystatechange = function(){
        if (d.isRequestSuccessful()){

            //Creating embed file and then append it to the pdf container in html.
            var el = document.getElementById('pdf-container');
            el.innerHTML = "";
            var em = document.createElement("object");
            em.type = "application/pdf";
            em.data = "data:application/pdf;base64," + d.xmlHttpRequestInstance.responseText; //Response text always should be encoded to the base64 encoding.
            em.width = "700px"; //Custom, so can be changed
            em.height = "1000px"; //Custom, so can be changed
            // el.innerHTML += '<embed width=100% height=100% type=\"application/pdf\" src=\"data:application/pdf;base64,' + d.xmlHttpRequestInstance.responseText + '></embed>';
            el.appendChild(em);
            smoothScroll();
            // alert(d.xmlHttpRequestInstance.responseText);
            // alert(el);
        }
    };
    // d.sendJSONRequest("/icc/writeOffPage/generateWriteOffAct?startdate=" + app.startDate, POST, JSON.stringify({"123": "123"}), true);
    d.sendPOSTRequest("/icc/writeOffPage/generateWriteOffAct", "writeOffActId=" + id);
    // d.sendPOSTRequest("/icc/WriteOffController.php", "startDate=" + app.startDate);
    // window.open("/icc/WriteOffController.php", "_blank");
}

function addNewWriteOffAct() {
    var request = new HttpRequest();
    request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
        if (request.isRequestSuccessful()) {
            console.log("Adding new request status: " + request.xmlHttpRequestInstance.responseText);
            if (document.getElementById("outputTable")) {
                getAllWriteOffActs();
            }
        }
    };
    request.sendPOSTRequest("/icc/writeOffPage/addNewWriteOffAct", "startDate=" + app.startDate + "&endDate=" + app.endDate);
}

function getAllWriteOffActs() {
    var request = new HttpRequest();
    request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
        if (request.isRequestSuccessful()) {
            //Remove old table if it exists
            var oldTable = document.getElementById('outputTable');
            if (oldTable) {
                oldTable.remove();
            }
            var parsedData = JSON.parse(request.xmlHttpRequestInstance.responseText);
            console.log(parsedData);
            var app = document.getElementsByClassName('main-container')[0];
            var prev = document.getElementById('pdf-container');
            var table = buildTableFromJson(parsedData);
            table.id = "outputTable";

            app.insertBefore(table, prev);
        }
    };
    request.sendPOSTRequest("/icc/writeOffPage/getAllWriteOffActs", "");
}

function deleteWriteOffAct(id) {
    var request = new HttpRequest();
    request.xmlHttpRequestInstance.onreadystatechange = function (ev) {
        if (request.isRequestSuccessful()) {
            console.log('DELETE REQUEST STATUS: ' + request.xmlHttpRequestInstance.responseText);
            getAllWriteOffActs();
        }
    };

    request.sendPOSTRequest("/icc/writeOffPage/deleteWriteOffAct", "writeOffActId=" + id);
}

function buildTableFromJson(jsonData) {
    var table = document.createElement('table');
    for (var i = 0; i < jsonData.length; i++) {
        var tr = table.insertRow();
        // tr.style = trStyle;
        for (var j = 0; j < jsonData[i].length; j++) {
            var td = tr.insertCell();

            // td.style = tdStyle;
            td.appendChild(document.createTextNode(jsonData[i][j]));
        }

        var tdGenerate = tr.insertCell();
        var tdDelete = tr.insertCell();
        var button = document.createElement('button');
        var buttonDelete = document.createElement('button');
        button.innerText = "Generate";
        buttonDelete.innerText = "Del";
        button.value = jsonData[i][0];
        buttonDelete.value = jsonData[i][0];
        button.className = "standard-button";
        buttonDelete.className = "standard-button";
        button.onclick = function (ev) { generateWriteOffAct(this.value); };
        buttonDelete.onclick = function (ev) { deleteWriteOffAct(this.value); };
        tdGenerate.appendChild(button);
        tdDelete.appendChild(buttonDelete);
    }

    return table;
}

function smoothScroll(){
    console.log('%c Navigation to the pdf-container element.', 'background: #222; color: #bada55');
    var currentScroll = document.getElementById("pdf-container").scrollHeight;
    // console.log(currentScroll);
    // console.log(document.documentElement.scrollTop);
    if (currentScroll > 0) {
        // window.requestAnimationFrame(smoothScroll);
        window.scrollTo (0,currentScroll);
    }
}