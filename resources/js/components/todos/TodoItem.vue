<template>
    <div
      class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md"
      :class="{
        'border-l-4 border-green-500': todo.completed,
        'border-l-4 border-red-500': !todo.completed && isOverdue(todo.due_date),
        'border-l-4 border-orange-400': !todo.completed && isDueToday(todo.due_date)
      }"
    >
      <div class="p-5 flex items-start">
        <div class="flex-shrink-0 mr-4">
          <button
            @click="$emit('toggle-complete', todo)"
            class="w-6 h-6 rounded-full border-2 flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            :class="todo.completed ? 'bg-green-500 border-green-500' : 'border-gray-300'"
          >
            <svg v-if="todo.completed" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
          </button>
        </div>
        <div class="flex-grow">
          <h3
            class="text-lg font-medium mb-1 transition-all duration-200"
            :class="{ 'line-through text-gray-500': todo.completed }"
          >
            {{ todo.title }}
          </h3>
          <p
            v-if="todo.description"
            class="text-gray-600 mt-1 transition-all duration-200"
            :class="{ 'text-gray-400': todo.completed }"
          >
            {{ todo.description }}
          </p>

          <!-- Affichage de la date d'échéance -->
          <div v-if="todo.due_date" class="mt-2 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span :class="{
              'text-red-600 font-medium': isOverdue(todo.due_date) && !todo.completed,
              'text-orange-500': isDueToday(todo.due_date) && !todo.completed,
              'text-gray-500': !isOverdue(todo.due_date) || todo.completed
            }">
              Échéance: {{ formatDate(todo.due_date, 'date') }}
              <span v-if="isOverdue(todo.due_date) && !todo.completed" class="text-red-600 font-medium">(En retard)</span>
              <span v-else-if="isDueToday(todo.due_date) && !todo.completed" class="text-orange-500 font-medium">(Aujourd'hui)</span>
            </span>
          </div>

          <!-- Affichage du rappel -->
          <div v-if="todo.reminder_at" class="mt-1 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="text-gray-500">
              Rappel: {{ formatDate(todo.reminder_at, 'datetime') }}
            </span>
          </div>

          <div class="mt-2 text-sm text-gray-500">
            Ajouté {{ formatDate(todo.created_at) }}
          </div>
        </div>
        <div class="flex-shrink-0 ml-4 flex items-center space-x-2">
          <button
            @click="$emit('edit-todo', todo)"
            class="text-gray-500 hover:text-blue-600 transition-colors duration-200"
            title="Modifier"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
          </button>
          <button
            @click="$emit('delete-todo', todo)"
            class="text-gray-500 hover:text-red-600 transition-colors duration-200"
            title="Supprimer"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      todo: {
        type: Object,
        required: true
      }
    },

    emits: ['toggle-complete', 'edit-todo', 'delete-todo'],

    methods: {
      formatDate(dateString, type = 'datetime') {
        if (!dateString) return '';

        const date = new Date(dateString);

        if (type === 'date') {
          return new Intl.DateTimeFormat('fr-FR', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }).format(date);
        }

        return new Intl.DateTimeFormat('fr-FR', {
          day: 'numeric',
          month: 'short',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        }).format(date);
      },

      isOverdue(dateString) {
        if (!dateString) return false;

        const dueDate = new Date(dateString);
        const today = new Date();

        // Comparer uniquement les dates sans les heures
        const dueDateOnly = new Date(dueDate.getFullYear(), dueDate.getMonth(), dueDate.getDate());
        const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        return dueDateOnly < todayOnly;
      },

      isDueToday(dateString) {
        if (!dateString) return false;

        const dueDate = new Date(dateString);
        const today = new Date();

        return (
        dueDate.getDate() === today.getDate() &&
        dueDate.getMonth() === today.getMonth() &&
        dueDate.getFullYear() === today.getFullYear()
      );
    }
  }
}
</script>
