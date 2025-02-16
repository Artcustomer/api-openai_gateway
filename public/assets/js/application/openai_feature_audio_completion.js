(function () {
    "use strict";

    let mediaRecorder;
    let audioChunks = [];
    let isRecording = false;
    let recordButton = select('#feature_audio_completion_button_record');
    let recordingProgressBar = select('#recordingProgressBar');

    navigator.mediaDevices.getUserMedia({audio: true})
        .then(stream => {
            handleUserMedia(stream)
        })
    ;

    function handleUserMedia(stream) {
        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.ondataavailable = e => {
            audioChunks.push(e.data);

            if (mediaRecorder.state === 'inactive') {
                let blob = new Blob(audioChunks, {type: 'audio/mp3'});
                let url = URL.createObjectURL(blob);

                console.log(url);
            }
        }
    }

    function toggleRecording() {
        if (isRecording === false) {
            console.log('Start...');

            startRecording();

            recordingProgressBar.classList.remove('d-none');

            recordButton.classList.remove('btn-outline-primary');
            recordButton.classList.add('btn-primary');
        } else {
            console.log('Stop!');

            stopRecording();

            recordingProgressBar.classList.add('d-none');

            recordButton.classList.remove('btn-primary');
            recordButton.classList.add('btn-outline-primary');
        }

        isRecording = !isRecording;
    }

    function startRecording() {
        audioChunks = [];

        mediaRecorder.start();
    }

    function stopRecording() {
        stop.disabled = true;

        mediaRecorder.stop();
    }

    if (recordButton) {
        on('click', '#feature_audio_completion_button_record', function (e) {
            toggleRecording();
        })
    }
})();