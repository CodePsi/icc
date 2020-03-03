// function buildTableFromJson(jsonData, trStyle, tdStyle) {
//     var table = document.createElement('table');
//     for (var i = 0; i < jsonData.length; i++) {
//         var tr = table.insertRow();
//         tr.style = trStyle;
//         for (var j = 0; j < jsonData[i].length; j++) {
//             var td = tr.insertCell();
//             td.style = tdStyle;
//             td.appendChild(document.createTextNode(jsonData[i][j]));
//         }
//     }
//
//     return table;
// }