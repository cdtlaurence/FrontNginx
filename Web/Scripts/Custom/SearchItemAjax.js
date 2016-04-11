function SearchItem(keywords, postcode, distance, resultsOrder, resultsNumber) {
    var xhttp;
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
    var number = document.getElementById("Advert").count;
    document.getElementById("searchDescription").innerHTML = "<strong class=\"text-danger\">" + number + "</strong> results were found for the search for <strong class=\"text-danger\">" + keywords + "</strong>";
    return false;
}