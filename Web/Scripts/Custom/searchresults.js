function checkAddress(postcode) {
    var xhttp;
    if (window.XMLHttpRequest) {
        xhttp = new XMLHttpRequest();
    } else {
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var httpUrl = "../Service/CheckAddress.php?postcode=" + postcode;
    xhttp.open("GET", httpUrl, false);
    xhttp.send();
    var postcodeDiv = document.getElementById("postcodeDiv");
    var iconSpan = document.getElementById("postcodeIcon");
    if (xhttp.responseText === "Found") {
        postcodeDiv.className = "form-group has-success has-feedback";
        iconSpan.className = "glyphicon glyphicon-ok form-control-feedback";
        return true;
    } else {
        postcodeDiv.className = "form-group has-error has-feedback";
        iconSpan.className = "glyphicon glyphicon-remove form-control-feedback";
        return false;
    }
}