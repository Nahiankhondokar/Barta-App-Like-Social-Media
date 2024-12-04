import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import App from './App.vue';
import Posts from './components/Post/Posts.vue';

 
const app = createApp(App);

app.use(router)
app.component('posts', Posts)
app.mount('#app');
