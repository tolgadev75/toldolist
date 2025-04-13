import './bootstrap';
import { createApp } from 'vue';
import TodoApp from './components/TodoApp.vue';

const app = createApp({});
app.component('todo-app', TodoApp);
app.mount('#app');
