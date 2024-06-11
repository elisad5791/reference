import { BitrixVue } from 'ui.vue3';

export class Counter {
    #application;

    constructor(rootNode) {
        this.rootNode = document.querySelector(rootNode);
    }

    start() {
        const context = this;

        this.#application = BitrixVue.createApp({
            name: 'Counter',
            data() {
                return {
                    counter: 0
                }
            },
            beforeCreate() {
                this.$bitrix.Application.set(context);
            },
            mounted() {
                setInterval(() => { this.counter++ }, 1000);
            },
            template: '<div>Counter: {{ counter}}</div>'
        });
        this.#application.mount(this.rootNode);
    }
}