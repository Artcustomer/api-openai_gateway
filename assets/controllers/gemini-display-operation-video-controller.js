import {Controller} from '@hotwired/stimulus';
import {getComponent} from '@symfony/ux-live-component';

export default class extends Controller {

    static values = {
        isLoading: Boolean,
        isDone: Boolean
    }

    async initialize() {
        this.component = await getComponent(this.element);
        this.component.on('render:started ', (component) => {

        });
        this.component.on('render:finished', (component) => {
            if (component.getData('isOperationFailed') === true) {
                component.set('isDone', true);
            } else {
                if (component.getData('isOperationDone') === true) {
                    component.set('isDone', true);
                } else {
                    setTimeout(() => {
                        this.reload();
                    }, '10000');
                }
            }
        });
        this.component.on('model:set', (model, value, component) => {
            switch (model) {
                case 'isDone':
                    if (value === true) {
                        this.setLoaderState('hide');
                    }
                    break;
            }
        });

        this.init();
    }

    connect() {
        this.element.classList.add('gemini-display-operation-video');
    }

    disconnect() {

    }

    init() {
        this.component.set('isDone', false);

        this.reload();
    }

    setLoaderState(state) {
        const loaderElement = document.querySelector('#display_operation_video_loader');

        if (loaderElement) {
            switch (state) {
                case 'show':
                    loaderElement.classList.remove('d-none');
                    break;

                case 'hide':
                    loaderElement.classList.add('d-none');
                    break;
            }
        }
    }

    reload() {
        this.component.action('load');
    }

    onClickDownloadVideo(e) {
		const videoElement = document.querySelector('#' + e.params.id);
		
		if (videoElement) {
			let videoSrc = videoElement.getElementsByTagName('source')[0].src;
        }
		
		e.preventDefault();
        e.stopPropagation();
    }
}