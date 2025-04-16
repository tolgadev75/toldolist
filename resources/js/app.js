import './bootstrap';
import { createApp } from 'vue';
import TodoApp from './components/TodoApp.vue';
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';

const app = createApp({});
app.use(VCalendar, {
    locale: 'fr-FR',
  });

app.component('todo-app', TodoApp);
app.mount('#app');
