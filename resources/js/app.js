import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import App from './App.vue';
import 'vue-toast-notification/dist/theme-sugar.css';

 
const app = createApp(App);

app.use(router)
app.mount('#app');
