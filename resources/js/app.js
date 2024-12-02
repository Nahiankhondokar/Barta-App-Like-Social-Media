import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import Posts from './components/Post/Posts.vue';
 
const app = createApp();

app.component('posts', Posts)
app.mount('#app');
