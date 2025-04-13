<template>
    <div class="mb-8 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <select
          :value="status"
          @change="$emit('update:status', $event.target.value)"
          class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          <option value="all">Toutes</option>
          <option value="active">En cours</option>
          <option value="completed">Terminées</option>
          <option value="due-today">À faire aujourd'hui</option>
          <option value="overdue">En retard</option>
        </select>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      search: {
        type: String,
        default: ''
      },
      status: {
        type: String,
        default: 'all'
      }
    },
    emits: ['update:search', 'update:status']
  }
  </script>
