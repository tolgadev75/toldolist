<template>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden transition-all duration-300 mb-8 hover:shadow-md">
      <div class="px-6 py-4">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ajouter une nouvelle tâche</h2>
        <div class="mb-4">
          <label for="task-title" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
          <input
            id="task-title"
            v-model="localTodo.title"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Titre de la tâche"
            @keyup.enter="submit"
          />
        </div>
        <div class="mb-4">
          <label for="task-description" class="block text-sm font-medium text-gray-700 mb-1">Description (optionnelle)</label>
          <textarea
            id="task-description"
            v-model="localTodo.description"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Description détaillée..."
            rows="2"
          ></textarea>
        </div>

        <!-- Champ date d'échéance -->
        <div class="mb-4">
          <label for="task-due-date" class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance</label>
          <input
            id="task-due-date"
            v-model="localTodo.due_date"
            type="date"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>

        <!-- Champ date de rappel -->
        <div class="mb-4">
          <label for="task-reminder" class="block text-sm font-medium text-gray-700 mb-1">Rappel (optionnel)</label>
          <input
            id="task-reminder"
            v-model="localTodo.reminder_at"
            type="datetime-local"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>

        <div class="flex justify-end">
          <button
            @click="submit"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!localTodo.title"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter
          </button>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      newTodo: {
        type: Object,
        required: true
      }
    },

    data() {
      return {
        localTodo: { ...this.newTodo }
      }
    },

    watch: {
      newTodo: {
        handler(newValue) {
          this.localTodo = { ...newValue };
        },
        deep: true
      }
    },

    methods: {
      submit() {
        if (!this.localTodo.title) return;
        this.$emit('add-todo', this.localTodo);
      }
    }
  }
  </script>
