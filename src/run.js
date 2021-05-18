let streaming = false;


navigator.mediaDevices.getUserMedia({ video: true, audio: false })
.then(function(stream) {
    video.srcObject = stream;
    video.play();
    streaming = true;
})
.catch(function(err) {
    console.log("An error occurred! " + err);
    streaming = false;
});
