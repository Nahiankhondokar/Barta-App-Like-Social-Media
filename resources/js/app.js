import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import Posts from './components/Post/Posts.vue';
import VueRouter from 'vue-router'
 
const app = createApp();

app.use(VueRouter)
app.component('posts', Posts)
app.mount('#app');
