<!-- TodoViewSelector.vue -->
<template>
    <div class="flex justify-end mb-4">
      <div class="inline-flex rounded-md shadow-sm">
        <button
          @click="$emit('update:view', 'list')"
          class="px-4 py-2 text-sm font-medium rounded-l-md border transition-colors"
          :class="modelValue === 'list'
            ? 'bg-blue-500 text-white border-blue-500'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
        >
          <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            Liste
          </span>
        </button>
        <button
          @click="$emit('update:view', 'calendar')"
          class="px-4 py-2 text-sm font-medium rounded-r-md border transition-colors"
          :class="modelValue === 'calendar'
            ? 'bg-blue-500 text-white border-blue-500'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
        >
          <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Calendrier
          </span>
        </button>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      modelValue: {
        type: String,
        required: true
      }
    },
    emits: ['update:view']
  }
  </script>
