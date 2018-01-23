function showHint(str) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
             document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", "./includes/searchExp.php?q=" + str, true);
    xmlhttp.send();
}