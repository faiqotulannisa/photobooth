<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Photobooth</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
#camera {
    position: relative;
    max-width: 400px;
    margin: auto;
}
#counter {
    position: absolute;
    inset: 0;
    display: none;
    justify-content: center;
    align-items: center;
    font-size: 80px;
    font-weight: bold;
    color: red;
    background: rgba(0,0,0,0.3);
}
</style>
</head>

<body class="bg-light">

<div class="container text-center py-4">
    <h2>📸 Photobooth</h2>

    <div id="camera" class="ratio ratio-4x3 mb-3">
        <video id="video" autoplay playsinline></video>
        <div id="counter" class="d-flex"></div>
    </div>

    <button id="snap" class="btn btn-primary btn-lg">
        Take Photo
    </button>

    <canvas id="canvas" width="400" height="300" class="d-none"></canvas>

    <div id="preview" class="row g-2 mt-4 justify-content-center"></div>
</div>

<script>
$(function () {

    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const counter = $('#counter');

    // OPEN CAMERA
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => video.srcObject = stream)
        .catch(() => alert('Camera access denied'));

    // COUNTDOWN
    function countdown() {
        let n = 3;
        counter.text(n).show();

        const timer = setInterval(() => {
            n--;
            if (n > 0) {
                counter.text(n);
            } else {
                clearInterval(timer);
                counter.hide();
                takePhoto();
            }
        }, 1000);
    }

    // TAKE PHOTO
    function takePhoto() {
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const image = canvas.toDataURL('image/png');

        upload(image);
    }

    // UPLOAD TO PHP
    function upload(image) {
        $.post('upload.php', { image: image }, function (res) {
            $('#preview').prepend(`
                <div class="col-4 col-md-2">
                    <img src="photos/${res.file}" class="img-fluid rounded shadow">
                </div>
            `);
        }, 'json');
    }

    // BUTTON
    $('#snap').click(function () {
        countdown();
    });

});
</script>

</body>
</html>
