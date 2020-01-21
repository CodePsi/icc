
function init() {
    document.getElementById('slider-left').onclick = sliderLeft;
}
function sliderLeft() {
    var polosa2 = document.getElementById('prizrak');
    var g = 1;
    // var polosa5 = document.getElementById('fon');
    // polosa5.style.zIndex = "18";
    // polosa5.style.opacity = "0.8";
    // polosa5.style.height = 100 + "vh";
    polosa2.style.opacity = "1";
    polosa2.style.zIndex = "20";
}
document.getElementById('slider-right').onclick = sliderLe;
function sliderLe() {
    var polosa3 = document.getElementById('prizrak');
    // var polosa4 = document.getElementById('fon');
    polosa3.style.opacity = 0;
    polosa3.style.zIndex = 2;
    // polosa4.style.zIndex = 0;
    // polosa4.style.opacity = 0;
    // polosa4.style.height = 90 + "vh";
}
document.getElementById('fon').onclick = sliderLe;
function sliderLe() {
    var polosa7 = document.getElementById('fon');
    var polosa8 = document.getElementById('prizrak');
    polosa8.style.opacity = 0;
    polosa8.style.zIndex = 2;
    polosa7.style.zIndex = 0;
    polosa7.style.opacity = 0;
    polosa7.style.height = 90 + "vh";
}