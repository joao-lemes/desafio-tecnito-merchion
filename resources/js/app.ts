import { createApp } from 'vue';
import App from './pages/App.vue';
import router from './lib/router';

const app = createApp(App);
app.use(router);
app.mount('#app');
