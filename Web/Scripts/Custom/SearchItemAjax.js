function SearchItem() {
    var xhttp;
    var keywords = document.getElementById("keywords").value;
    var resultsNumber = document.getElementById("resultsNumberSelect").value;
    if (window.XMLHttpRequest) {
        xhttp = new XMLHttpRequest();
    } else {
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var httpUrl = "../Service/SearchItems.php?keywords=" + keywords + "&resultsNumber=" + resultsNumber;
    xhttp.open("GET", httpUrl, false);
    xhttp.send();
    document.getElementsByClassName("search-result").innerHTML = "";
    document.getElementById("searchResults").innerHTML = xhttp.responseText;
    var numberString = document.getElementsByTagName("img").length;
    var number = parseInt(numberString);
    number = number - 1;
    console.log(number);
    if (number === 0) {
        document.getElementById("searchDescription").innerHTML = "<strong class=\"text-danger\">0</strong> results were found for the search for <strong class=\"text-danger\">" + keywords + "</strong>";
    } else {
        document.getElementById("searchDescription").innerHTML = "<strong class=\"text-danger\">" + number + "</strong> results were found for the search for <strong class=\"text-danger\">" + keywords + "</strong>";
    }
    return false;
}