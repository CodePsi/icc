//WHEN THERE WILL BE NEW COMPUTER WITH SUPPORT ECMASCRIPT 2017
// async function collectData() {
//
//     let valueNameOfTheItem = document.getElementById("nameOfTheEmployee").value;
//     let valueSurnameOfTheItem = document.getElementById("surnameOfTheEmployee").value;
//     let valuePatronymicOfTheEmployee = document.getElementById("patronymicOfTheEmployee").value;
//     let valueStatus = document.getElementById("status").value;
//     let valueContactNumber = document.getElementById("contactNumber").value;
//     let valuePosition = document.getElementById("position").value;
//
//     let response = await fetch("/icc/EmployeeController.php", {
//         method: "POST",
//         headers: {
//             "Accept": "application/json",
//             "Content-Type": "application/json"
//         },
//         body: JSON.stringify({"name":valueNameOfTheItem,"surname":valueSurnameOfTheItem, "patronymic":valuePatronymicOfTheEmployee,
//             "status":valueStatus, "contactNumber":valueContactNumber, "position":valuePosition})
//     }).catch(reason => console.log(reason)).then(value => location.replace(value.url));
//     // console.log(response.text().then(value => console.log(value)));
//     // response.catch(function (err) {
//     //     console.log(err);
//     // })
// }

// async function getEmployees() {
//     let response = await fetch("/icc/EmployeeController.php", {
//         method: "GET"
//     });
//     let parsed = 0;
//     response.text().then(value => parsed = JSON.parse(value));
//
//     // let table = document.getElementById()
// }

function selectedChief() {
    document.getElementById('position').value = "Chief";
}


function toAddNewEmployee() { /*TODO FIX General function for redirects*/
    window.location = "/icc/addNewEmployee.php";
}