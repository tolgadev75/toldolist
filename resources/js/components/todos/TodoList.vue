<template>
    <div>
      <h2 class="text-xl font-semibold text-gray-800 mb-4">
        {{ todos.length }}
        {{ todos.length > 1 ? 'tâches' : 'tâche' }}
        {{ getFilterLabel() }}
      </h2>

      <transition-group name="list" tag="div" class="space-y-3">
        <TodoItem
        v-for="todo in todos"
        :key="todo.id"
        :todo="todo"
        @toggle-complete="$emit('toggle-complete', todo)"
        @edit-todo="$emit('edit-todo', todo)"
        @delete-todo="$emit('delete-todo', todo)"
    />
      </transition-group>
    </div>
  </template>

  <script>
  import TodoItem from './TodoItem.vue';
  import './TodoList.css';

  export default {
    components: {
      TodoItem
    },

    props: {
      todos: {
        type: Array,
        required: true
      },
      filterStatus: {
        type: String,
        default: 'all'
      }
    },

    emits: ['toggle-complete', 'edit-todo', 'delete-todo'],

    methods: {
      getFilterLabel() {
        switch (this.filterStatus) {
          case 'active': return 'en cours';
          case 'completed': return 'terminées';
          case 'due-today': return 'à faire aujourd\'hui';
          case 'overdue': return 'en retard';
          default: return '';
        }
      }
    }
  }
  </script>

