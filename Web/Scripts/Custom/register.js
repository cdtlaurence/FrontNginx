// AJAX function to call CheckName.php
function checkName(uname) {
    var xhttp;
    if (window.XMLHttpRequest) {
        xhttp = new XMLHttpRequest();
    } else {
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var httpUrl = "../Service/CheckName.php?n=" + uname;
    xhttp.open("GET", httpUrl, false);
    xhttp.send();
    return xhttp.responseText;
}

// validate user name 
function validateUsername(uname) {
    var usernameDiv = document.getElementById("usernameDiv");
    var iconSpan = document.getElementById("usernameIcon");
    var message = document.getElementById("usernameMessage");
    if (uname.length < 6) {
        usernameDiv.className = "form-group has-warning has-feedback";
        iconSpan.className = "glyphicon glyphicon-warning-sign form-control-feedback";
        message.innerHTML = "Min 6 characters";
        message.style.color = "orange";
        return false;
    }
    // AJAX check 
    switch (checkName(uname)) {
        case "tooShort":
            usernameDiv.className = "form-group has-error has-feedback";
            iconSpan.className = "glyphicon glyphicon-remove form-control-feedback";
            message.innerHTML = "Too short";
            message.style.color = "red";
            return false;
        case "taken":
            usernameDiv.className = "form-group has-error has-feedback";
            iconSpan.className = "glyphicon glyphicon-remove form-control-feedback";
            message.innerHTML = "Not available";
            message.style.color = "red";
            return false;
        default:
            usernameDiv.className = "form-group has-success has-feedback";
            iconSpan.className = "glyphicon glyphicon-ok form-control-feedback";
            message.innerHTML = "Available";
            message.style.color = "green";
            return true;
    }
}

// validate password
function validatePassword(password) {
    return true;
}

// validate password
function validateEmail(email) {
    var message = document.getElementById("emailMessage");
    var username = document.getElementById("usernameInput").value;
    if (username === email) {
        message.innerHTML = "Username and email address must be different";
        message.style.color = "red";
        return false;
    }
    message.innerHTML = "";
    message.style.color = "green";
    return true;
}

function validateForm(theForm) {
    var valid = true;
    if (!validateUsername(theForm.usernameInput.value)) valid = false;
    if (!validatePassword(theForm.passwordInput.value)) valid = false;
    if (!validateEmail(theForm.emailInput.value)) valid = false;
    if (valid) return true;
    else return false;
}