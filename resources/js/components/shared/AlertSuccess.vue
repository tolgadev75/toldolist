<template>
    <div class="fixed top-5 right-5 z-50 w-80 transform transition-all duration-300 ease-in-out"
         :class="[show ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0']">
      <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md">
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span>{{ message }}</span>
          <button @click="$emit('close')" class="ml-auto text-green-500 hover:text-green-700 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      message: {
        type: String,
        required: true
      },
      show: {
        type: Boolean,
        default: true
      }
    },

    emits: ['close'],

    mounted() {
      if (this.show) {
        setTimeout(() => {
          this.$emit('close');
        }, 3000);
      }
    }
  }
  </script>
