<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden" @click.stop>
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-800">Modifier la tâche</h3>
        </div>
        <div class="p-6">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
            <input
              v-model="localTodo.title"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Titre de la tâche"
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="localTodo.description"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Description (optionnelle)"
              rows="3"
            ></textarea>
          </div>

          <!-- Champ date d'échéance dans le modal d'édition -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance</label>
            <input
              v-model="localTodo.due_date"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <!-- Champ date de rappel dans le modal d'édition -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Rappel</label>
            <input
              v-model="localTodo.reminder_at"
              type="datetime-local"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
          </div>

          <div class="mb-4">
            <label class="flex items-center">
              <input
                type="checkbox"
                v-model="localTodo.completed"
                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
              />
              <span class="ml-2 text-gray-700">Tâche terminée</span>
            </label>
          </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
          <button
            @click="$emit('cancel')"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
          >
            Annuler
          </button>
          <button
            @click="save"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            :disabled="!localTodo.title"
          >
            Enregistrer
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

    emits: ['save', 'cancel'],

    data() {
      return {
        localTodo: { ...this.todo }
      }
    },

    watch: {
      todo: {
        handler(newValue) {
          this.localTodo = { ...newValue };
        },
        deep: true
      }
    },

    methods: {
      save() {
        if (!this.localTodo.title) return;
        this.$emit('save', this.localTodo);
      }
    }
  }
  </script>
