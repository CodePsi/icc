const POST = "POST";
const GET = "GET";

var HttpRequest = function(){
    this.xmlHttpRequestInstance = new XMLHttpRequest();


};
HttpRequest.prototype.sendJSONRequest = function(url, method, jsonData){
    this.xmlHttpRequestInstance.open(method, url, true);
    this.xmlHttpRequestInstance.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    this.xmlHttpRequestInstance.send(jsonData);
};

HttpRequest.prototype.sendPOSTRequest = function(url, data) {
    this.xmlHttpRequestInstance.open("POST", url, true);
    this.xmlHttpRequestInstance.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    this.xmlHttpRequestInstance.send(data);
};

HttpRequest.prototype.sendGETRequest = function(url, data) {
    this.xmlHttpRequestInstance.open("GET", url, true);
    this.xmlHttpRequestInstance.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    this.xmlHttpRequestInstance.send(data);
};

HttpRequest.prototype.sendPUTRequest = function(url, data) {
    this.xmlHttpRequestInstance.open("PUT", url, true);
    this.xmlHttpRequestInstance.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    this.xmlHttpRequestInstance.send(data);
};

HttpRequest.prototype.sendPATCHRequest = function(url, data) {
    this.xmlHttpRequestInstance.open("PATCH", url, true);
    this.xmlHttpRequestInstance.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    this.xmlHttpRequestInstance.send(data);
};

HttpRequest.prototype.sendDELETERequest = function(url, data) {
    this.xmlHttpRequestInstance.open("DELETE", url, true);
    this.xmlHttpRequestInstance.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    this.xmlHttpRequestInstance.send(data);
};
// HttpRequest.prototype.onReadyStateChangeEvent = function (innerFunction, successStatus) {
//     if (this.xmlHttpRequestInstance.readyState === 4) {
//         if (this.xmlHttpRequestInstance === successStatus) {
//             innerFunction();
//         }
//     }
// };
HttpRequest.prototype.isRequestSuccessful = function () {
    return this.xmlHttpRequestInstance.readyState === 4 && this.xmlHttpRequestInstance.status === 200;
};