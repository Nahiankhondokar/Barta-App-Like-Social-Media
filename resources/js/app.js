import './bootstrap';
import { createApp } from 'vue';
import Test from './components/Test/Test.vue';
import App from './App.vue';
import HelloHi from './components/HelloHi.vue';
 
const app = createApp({});
app.component('hello-hi', HelloHi)
app.mount('#app');
