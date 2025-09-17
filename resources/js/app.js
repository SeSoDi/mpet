import './bootstrap';
import { createApp } from 'vue';
import Welcome from './components/Welcome.vue';

// Create Vue app
const app = createApp({
    components: {
        Welcome
    }
});

// Mount the app if we have a target element
const el = document.getElementById('app');
if (el) {
    app.mount(el);
}
