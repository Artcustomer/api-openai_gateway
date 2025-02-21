import {Controller} from '@hotwired/stimulus';
import {getComponent} from '@symfony/ux-live-component';
import AudioMotionAnalyzer from 'audiomotion-analyzer';

export default class extends Controller {
    mediaRecorder = null;
    timerId = null;
    elapsedTime = 0;
    audioChunks = [];
    isRecording = false;
    formElement = null;
    recordButton = null;
    audioMotionAnalyzer = null;

    static values = {
        isLoading: Boolean
    }

    async initialize() {
        this.component = await getComponent(this.element);
        this.component.on('render:started ', (component) => {
            this.stopAudio();
        });
        this.component.on('render:finished', (component) => {
            if (component.getData('isLoading') === false) {
                if (component.getData('isResponseSuccess') === true) {
                    this.handleAudio();
                }

                this.toggleFormState(false);
            }
        });
        this.component.on('model:set', (model, value, component) => {
            switch (model) {
                case 'isPrompting':
                    if (value === true) {
                        this.stopAudio();
                        this.resetUi();
                    }
                    break;
                case 'feature_audio_completion.file':
                    if (value !== null) {
                        setTimeout(() => {
                            this.submitData();
                        }, '100');
                    }
                    break;
            }
        });

        this.initUi();
        this.initMediaDevices();
    }

    connect() {
        this.element.classList.add('openai-feature-audio-completion');
    }

    disconnect() {
        this.mediaRecorder = null;
        this.timerId = null;
        this.elapsedTime = null;
        this.audioChunks = [];
        this.isRecording = false;
    }

    initMediaDevices() {
        navigator.mediaDevices.getUserMedia({audio: true})
            .then(stream => {
                this.handleUserMedia(stream)
            })
        ;
    }

    initUi() {
        this.formElement = document.querySelector('#feature_audio_completion_form');
        this.recordButton = document.querySelector('#feature_audio_completion_button_record');

        this.formElement.addEventListener('submit', this.onSubmitForm.bind(this), false);
        this.recordButton.addEventListener('click', this.onClickRecordButton.bind(this), false);
    }

    resetUi() {
        const selectors = [
            '#feature_audio_completion_response_alert',
            '#motion_analyzer_container'
        ];

        if (this.audioMotionAnalyzer) {
            this.audioMotionAnalyzer.destroy();
            this.audioMotionAnalyzer = null;
        }

        selectors.forEach(function (value, index) {
            let element = document.querySelector(value);

            if (element) {
                element.remove();
            }
        });
    }

    setAudioRecordButtonState(state) {
        const audioButton = document.querySelector('#feature_audio_completion_audio_control');

        if (audioButton) {
            switch (state) {
                case 'play':
                    audioButton.getElementsByTagName('i')[0].classList.add('bi-play');
                    audioButton.getElementsByTagName('i')[0].classList.remove('bi-stop');
                    break;

                case 'stop':
                    audioButton.getElementsByTagName('i')[0].classList.add('bi-stop');
                    audioButton.getElementsByTagName('i')[0].classList.remove('bi-play');
                    break;
            }
        }
    }

    initMotionAnalyzer(audioElement) {
        const container = document.getElementById('motion_analyzer_container');
        const options = {
            mode: 3,
            barSpace: .6,
            overlay: true,
            showBgColor: true,
            bgAlpha: 0,
            fillAlpha: 0,
            ledBars: false,
            trueLeds: true,
            radial: true,
            splitGradient: true,
            lumiBars: true,
            showPeaks: false,
            outlineBars: false,
            showScaleX: false,
            gradient: 'steelblue',
            colorMode: 'gradient',
        };

        this.audioMotionAnalyzer = new AudioMotionAnalyzer(
            container,
            {
                source: audioElement,
                height: 200,
            }
        );
        this.audioMotionAnalyzer.setOptions(options);
    }

    handleAudio() {
        const audioElement = this.element.querySelector('audio');

        if (audioElement) {
            audioElement.addEventListener('play', this.onPlayAudio.bind(this));
            audioElement.addEventListener('pause', this.onPauseAudio.bind(this));

            this.initMotionAnalyzer(audioElement);
        }
    }

    stopAudio() {
        const audioElements = this.element.querySelectorAll('audio');

        if (audioElements.length > 0) {
            const callback = function (element, index) {
                element.removeEventListener('play', this.onPlayAudio.bind(this));
                element.removeEventListener('pause', this.onPauseAudio.bind(this));
                element.pause();
                element.currentTime = 0;
                element.src = '';
                element.remove();
            }

            audioElements.forEach(callback.bind(this));
        }
    }

    toggleAudio() {
        const audioElement = this.element.querySelector('audio');

        if (audioElement) {
            if (!audioElement.paused) {
                audioElement.pause();
                audioElement.currentTime = 0;
            } else {
                audioElement.currentTime = 0;
                audioElement.play();
            }
        }
    }

    toggleRecording() {
        if (this.isRecording === false) {
            this.component.set('isPrompting', true);

            this.startRecording();
            this.startTimer();
        } else {
            this.component.set('isPrompting', false);

            this.stopRecording();
            this.stopTimer();
        }

        this.isRecording = !this.isRecording;
    }

    startRecording() {
        this.audioChunks = [];
        this.mediaRecorder.start();
    }

    stopRecording() {
        stop.disabled = true;

        this.mediaRecorder.stop();
    }

    startTimer() {
        if (this.timerId) {
            return;
        }

        this.updateUITimer(0);

        this.elapsedTime = 0;
        this.timerId = setInterval(() => {
            this.elapsedTime++;

            this.updateUITimer(this.elapsedTime);
        }, 1000);
    }

    stopTimer() {
        clearInterval(this.timerId);

        this.timerId = null;
        this.elapsedTime = 0;
    }

    updateUITimer(time) {
        this.recordButton.getElementsByTagName('p')[0].innerHTML = formatTime(time);
    }

    toggleFormState(state) {
        Array.from(this.formElement.elements).forEach(formField => formField.disabled = state);
    }

    setRecordToFile(blob, url) {
        const fileFormField = document.querySelector('#feature_audio_completion_file');
        const extension = blob.type.split(/[\/]+/).pop();
        const name = url.split(/[\/]+/).pop();
        const file = new File([blob], name + '.' + extension, {
            type: 'application/octet-stream',
            lastModified: new Date()
        });
        const dataTransfer = new DataTransfer();

        dataTransfer.items.add(file);

        fileFormField.files = dataTransfer.files;
        fileFormField.dispatchEvent(new Event('change', {'bubbles': true}));
    }

    submitData() {
        const submitButton = document.querySelector('#feature_audio_completion_save');

        submitButton.click();
    }

    onPlayAudio(e) {
        this.setAudioRecordButtonState('stop');
    }

    onPauseAudio(e) {
        this.setAudioRecordButtonState('play');
    }

    onClickRecordButton(e) {
        this.toggleRecording();
    }

    onSubmitForm(e) {
        this.toggleFormState(true);
    }

    handleUserMedia(stream) {
        this.mediaRecorder = new MediaRecorder(stream);
        this.mediaRecorder.ondataavailable = e => {
            this.audioChunks.push(e.data);

            if (this.mediaRecorder.state === 'inactive') {
                const blob = new Blob(this.audioChunks, {type: 'audio/mp3'});
                const url = URL.createObjectURL(blob);

                this.setRecordToFile(blob, url)
            }
        }
    }

    onClickAudioControl(e) {
        this.toggleAudio();
    }
}