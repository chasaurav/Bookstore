import './bootstrap';
import { createApp } from 'vue';
import mitt from 'mitt';

const app = createApp({});

class VueEventWrapper {
    constructor() {
        this.emitter = mitt();
    }

    fire (event, data) {
        this.emitter.emit(event, data);
    }

    listen (event, callBack) {
        this.emitter.on(event, callBack);
    }
}

window.CustomEvent = new VueEventWrapper();

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import Shop from './components/Shop.vue';
import BookList from './components/BookList.vue';
import CreateEditBook from './components/CreateEditBook.vue';
import Toaster from './components/Toaster.vue';

app.component('toaster', Toaster);
app.component('book-list', BookList);
app.component('create-edit-book', CreateEditBook);
app.component('shop', Shop);
app.mount('#app');