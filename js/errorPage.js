// async function init() {
//     // alert(location.search);
//     // alert(location.search);
//     // alert(location.search);
//     // alert(location.search);
//     let response = await fetch("/icc/ErrorPage.php" + location.search, {
//             method : "GET"
//         });
//         // .then(value => value.text().then(value1 => console.log(value1)));
//     response.text().then(value => document.getElementById("error_text").innerText = value);
//
//
// }

var app = new Vue({
    el: '#app',
    data: {
        responseCode: '',
        message: ''
    },
    created: function () {

    }
});

function init() {
    var url = decodeURIComponent(location.search).split('&');
    app.message = url[0].split('=')[1];
    app.responseCode = url[1].split('=')[1];
}