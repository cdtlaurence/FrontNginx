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
    var postcodeMessage = document.getElementById("postcodeMessage");
    if (xhttp.responseText === "Found") {
        postcodeDiv.className = "form-group has-success has-feedback";
        iconSpan.className = "glyphicon glyphicon-ok form-control-feedback";
        postcodeMessage.innerHTML = "Postcode okay";
        postcodeMessage.style.color = "green";
        return true;
    } else {
        postcodeDiv.className = "form-group has-error has-feedback";
        iconSpan.className = "glyphicon glyphicon-remove form-control-feedback";
        postcodeMessage.innerHTML = "Postcode not found";
        postcodeMessage.style.color = "red";
        return false;
    }
}

function validateForm(theForm) {
    var valid = true;
    if (!checkAddress(theForm.postcodeInput.value)) valid = false;
    if (valid) return true;
    else return false;
}