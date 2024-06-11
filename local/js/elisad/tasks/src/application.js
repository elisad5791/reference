import { BitrixVue } from 'ui.vue3';
import { Dom } from 'main.core';

import { TaskList } from './component/task-list';

export class TaskManager {
    #application;

    constructor(rootNode) {
        this.rootNode = document.querySelector(rootNode);
    }

    start() {
        const button = Dom.create('button', {
            text: 'Открыть компонент',
            events: {
                click: () => this.attachTemplate()
            }
        });
        Dom.append(button, this.rootNode);
    }

    attachTemplate() {
        const context = this;

        this.#application = BitrixVue.createApp({
            name: 'TaskManager',
            components: { TaskList },
            beforeCreate() {
                this.$bitrix.Application.set(context);
            },
            template: '<TaskList/>'
        });
        this.#application.mount(this.rootNode)
    }

    detachTemplate() {
        if (this.#application) {
            this.#application.unmount();
        }
        this.start();
    }
}
