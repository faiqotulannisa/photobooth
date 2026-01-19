<!DOCTYPE html>
<html>
<head>
    <title>Laravel Photobooth</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
#counter {
    position: absolute;
    inset: 0;
    display: none;
    justify-content: center;
    align-items: center;
    font-size: 100px;
    font-weight: bold;
    color: red;
    background: rgba(0,0,0,0.2); /* dark overlay */
    z-index: 10;
}
#camera-wrapper {
    position: relative;
    width: 400px;
    height: 300px;
}
</style>

</head>
<body>

<h2>📸 Laravel Photobooth</h2>
<div id="counter" style="
    font-size: 80px;
    font-weight: bold;
    color: red;
    position: absolute;
    top: 150px;
    left: 180px;
    display: none;
">
</div>
<div id="camera-wrapper" style="position: relative; width: 400px; height: 300px;">
    <video id="video" width="400" height="300" autoplay playsinline></video>

    <!-- GRID -->
    <div class="grid"></div>
</div>
<div id="counter"></div>

<br><br>
<button id="snap">Take Photo</button>

<canvas id="canvas" width="400" height="300" style="display:none;"></canvas>
<div id="preview" style="display:flex; gap:10px; margin-top:20px;"></div>

<script>
$(document).ready(function () {

    const video  = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx    = canvas.getContext('2d');
    const counterEl = $('#counter');
    const preview = $('#preview');

    let shots = [];
    let currentShot = 0;
    const maxShots = 3;

    // OPEN CAMERA
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
            video.play();
        });

    // COUNTDOWN 3 2 1
    function startCountdown() {
    let count = 3;

    counterEl
        .css({ color: 'red' })
        .text(count)
        .show()
        .css('display', 'flex');

    const timer = setInterval(() => {
        count--;

        if (count > 0) {
            counterEl.text(count);
        } else {
            clearInterval(timer);
            counterEl.hide();
            capturePhoto();
        }
    }, 1000);
}


    // CAPTURE PHOTO
    function capturePhoto() {
        canvas.width = 400;
        canvas.height = 300;

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imgData = canvas.toDataURL('image/png');
        shots.push(imgData);

        preview.append(`
            <img src="${imgData}" width="120" style="border:2px solid #333">
        `);

        currentShot++;

        if (currentShot < maxShots) {
            // pause 1 second before next countdown
            setTimeout(() => {
                startCountdown();
            }, 1000);
        } else {
            counterEl
                .css({ fontSize: '36px', color: 'lime' })
                .text('Done 🎉')
                .show()
                .delay(1500)
                .fadeOut();
        }
    }

    // BUTTON
    $('#snap').on('click', function () {
        shots = [];
        currentShot = 0;
        preview.empty();
        startCountdown();
    });

});
</script>




</body>
</html>
