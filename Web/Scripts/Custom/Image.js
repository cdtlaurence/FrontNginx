var uploadfiles = document.querySelector("input[id=fileInput]");
uploadfiles.addEventListener("change", function () {
    var galleryId = "gallery";
    var gallery = document.getElementById(galleryId);
    gallery.innerHTML = "";
    var files = this.files;
    for (var i = 0; i < files.length; i++) {
        previewImage(this.files[i]);
    }
    var imagesHeaderId = "imagesHeader";
    var images = document.getElementById(imagesHeaderId);
    images.className = "";
}, false);

function previewImage(file) {
    var imageType = /image.*/;
    if (!file.type.match(imageType)) {
        throw "File Type must be an image";
    }
    var galleryId = "gallery";
    var gallery = document.getElementById(galleryId);
    var thumb = document.createElement("div");
    thumb.classList.add("thumbnail");
    var img = document.createElement("img");
    img.file = file;
    thumb.appendChild(img);
    gallery.appendChild(thumb);
    var reader = new FileReader();
    reader.onload = (function (aImg) { return function (e) { aImg.src = e.target.result; }; })(img);
    reader.readAsDataURL(file);
}