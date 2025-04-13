<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 overflow-hidden" @click.stop>
        <div class="px-6 py-4 bg-red-50 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-800">Confirmation de suppression</h3>
        </div>
        <div class="p-6">
          <div class="flex items-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <div>
              <p class="text-gray-700">Êtes-vous sûr de vouloir supprimer cette tâche ?</p>
              <p v-if="todo" class="font-semibold text-gray-900 mt-1">{{ todo.title }}</p>
              <p class="text-gray-500 text-sm mt-2">Cette action est irréversible.</p>
            </div>
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
            @click="$emit('confirm')"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
          >
            Confirmer la suppression
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
    emits: ['confirm', 'cancel']
  }
  </script>
