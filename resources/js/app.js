import './bootstrap';
import { createApp } from 'vue';
import Test from './components/Test/Test.vue';
 
createApp({})
  .component('Test', Test)
  .mount('#app')