<template>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
      <h2 class="text-lg font-medium text-gray-800 mb-4">Calendrier des tâches</h2>

      <div class="calendar-wrapper">
        <v-calendar
          :attributes="calendarAttributes"
          :first-day-of-week="1"
          @dayclick="onDayClick"
          class="custom-calendar"
          expanded
          transparent
          :key="calendarKey"
        />
      </div>

      <div v-if="showDayTasks" class="day-tasks bg-white rounded-xl shadow-md p-4 mt-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-medium text-gray-800">
            Tâches du {{ formatDay(selectedDay) }}
          </h3>
          <div class="flex space-x-2">
            <button
              @click="createTaskForSelectedDay"
              class="px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition-colors flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Nouvelle tâche
            </button>
            <button @click="showDayTasks = false" class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>

        <div v-if="tasksForSelectedDay.length === 0" class="text-gray-500 text-center py-2">
          Aucune tâche pour ce jour
        </div>

        <ul v-else class="space-y-2">
          <li v-for="task in tasksForSelectedDay" :key="task.id"
              class="flex items-center justify-between p-3 rounded hover:bg-gray-50 border border-gray-100"
              :class="{ 'bg-green-50 border-green-100': task.completed }">
            <div class="flex items-center">
              <span class="w-3 h-3 rounded-full mr-2"
                    :class="task.completed ? 'bg-green-500' : 'bg-blue-500'"></span>
              <span :class="{ 'line-through text-gray-400': task.completed }">
                {{ task.title }}
              </span>
            </div>
            <div class="flex space-x-2">
              <button
                @click="$emit('toggle-complete', task)"
                class="p-1 text-gray-500 hover:text-blue-500 transition-colors"
                :title="task.completed ? 'Marquer comme non terminée' : 'Marquer comme terminée'"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </button>
              <button
                @click="$emit('edit-todo', task)"
                class="p-1 text-gray-500 hover:text-yellow-500 transition-colors"
                title="Modifier"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                @click="$emit('delete-todo', task)"
                class="p-1 text-gray-500 hover:text-red-500 transition-colors"
                title="Supprimer"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      todos: {
        type: Array,
        required: true
      }
    },

    emits: ['toggle-complete', 'edit-todo', 'delete-todo', 'create-todo-modal'],

    data() {
      return {
        showDayTasks: false,
        selectedDay: null,
        tasksForSelectedDay: [],
        calendarKey: 0
      }
    },

    watch: {
      todos: {
        handler() {
          this.calendarKey += 1;

          if (this.showDayTasks && this.selectedDay) {
            this.updateTasksForSelectedDay();
          }
        },
        deep: true
      }
    },

    computed: {
      calendarAttributes() {
        const tasksByDate = {};

        this.todos.forEach(todo => {
          if (!todo.due_date) return;

          const dateKey = this.formatDateForKey(todo.due_date);

          if (!tasksByDate[dateKey]) {
            tasksByDate[dateKey] = [];
          }
          tasksByDate[dateKey].push(todo);
        });

        const attributes = [];

        // Pour chaque date avec des tâches, ajouter un attribut
        Object.keys(tasksByDate).forEach(dateKey => {
          const tasks = tasksByDate[dateKey];
          const completedCount = tasks.filter(t => t.completed).length;
          const activeCount = tasks.length - completedCount;

          let dotColor = 'blue';
          if (completedCount === tasks.length) {
            dotColor = 'green';
          } else if (this.isDateOverdue(dateKey) && activeCount > 0) {
            dotColor = 'red';
          }

          // Création de la date à partir de la chaîne YYYY-MM-DD
          const [year, month, day] = dateKey.split('-').map(Number);
          // Mois est 0-indexé dans JavaScript
          const date = new Date(year, month - 1, day);

          attributes.push({
            dot: {
              color: dotColor,
              class: 'opacity-75'
            },
            dates: date,
            popover: {
              label: `${tasks.length} tâche(s): ${activeCount} actives, ${completedCount} terminées`
            }
          });
        });

        return attributes;
      }
    },

    methods: {
      formatDateForKey(dateString) {
        const date = new Date(dateString);
        // S'assurer que nous utilisons la bonne timezone pour la comparaison
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
      },

      isDateOverdue(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        return new Date(date.getFullYear(), date.getMonth(), date.getDate()) <
               new Date(today.getFullYear(), today.getMonth(), today.getDate());
      },

      onDayClick(day) {
        // Formater la date cliquée correctement
        this.selectedDay = day.date;

        // Mettre à jour la liste des tâches pour ce jour
        this.updateTasksForSelectedDay();

        // Afficher le popup
        this.showDayTasks = true;
      },

      // Méthode pour mettre à jour la liste des tâches du jour sélectionné
      updateTasksForSelectedDay() {
        if (!this.selectedDay) return;

        const clickedDate = this.formatDateForKey(this.selectedDay);

        this.tasksForSelectedDay = this.todos.filter(todo => {
          if (!todo.due_date) return false;
          const todoDate = this.formatDateForKey(todo.due_date);
          return todoDate === clickedDate;
        });
      },

      formatDay(date) {
        if (!date) return '';

        const options = { weekday: 'long', day: 'numeric', month: 'long' };
        return new Date(date).toLocaleDateString('fr-FR', options);
      },

      // Méthode pour créer une tâche
      createTaskForSelectedDay() {
        if (!this.selectedDay) return;

        const formattedDate = this.formatDateForKey(this.selectedDay);

        this.$emit('create-todo-modal', formattedDate);
      }
    }
  }
  </script>

