const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const result = document.getElementById("result");
const counter = $("#counter");

navigator.mediaDevices.getUserMedia({ video: true })
.then(stream => video.srcObject = stream);

$("#takePhoto").click(function () {
    let count = 3;
    counter.text(count).show();

    let interval = setInterval(() => {
        count--;
        counter.text(count);

        if (count === 0) {
            clearInterval(interval);
            counter.hide();
            capturePhoto();
        }
    }, 1000);
});

function capturePhoto() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    canvas.getContext("2d").drawImage(video, 0, 0);
    let imageData = canvas.toDataURL("image/png");

    $.post("capture.php", { image: imageData }, function (res) {
        result.src = res;
    });
}

