<template>
    <div class="w-full">
      <TodoViewSelector v-model="currentView" @update:view="setView" />

      <!-- Filtres et recherche -->
      <TodoFilter
        v-model:search="searchQuery"
        v-model:status="filterStatus"
      />

      <!-- Notifications -->
      <AlertError v-if="error" :message="error" @close="error = null" />
      <AlertSuccess v-if="successMessage" :message="successMessage" @close="successMessage = null" />


      <!-- Formulaire d'ajout -->
      <TodoForm
        ref="todoForm"
        :new-todo="newTodo"
        @add-todo="addTodo"
        class="todo-form"
      />

      <!-- État de chargement -->
      <div v-if="loading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>

      <div v-else>
        <div v-if="currentView === 'calendar' || alwaysShowCalendar">
          <TodoCalendar
            :todos="todos"
            :key="calendarRefreshKey"
            @toggle-complete="toggleComplete"
            @edit-todo="editTodo"
            @delete-todo="deleteTodo"
            @create-todo-modal="openCreateTodoModal"
          />
        </div>

        <template v-if="currentView === 'list'">
          <div v-if="filteredTodos.length === 0" class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-800 mb-1">Aucune tâche trouvée</h3>
            <p class="text-gray-500">
              {{ getEmptyStateMessage() }}
            </p>
          </div>

          <TodoList
            v-else
            :todos="filteredTodos"
            :filter-status="filterStatus"
            @toggle-complete="toggleComplete"
            @edit-todo="editTodo"
            @delete-todo="deleteTodo"
          />
        </template>
      </div>

      <TodoEditModal
        v-if="editingTodo"
        :todo="editingTodo"
        @save="saveEdit"
        @cancel="cancelEdit"
      />
    </div>
    <DeleteConfirmModal
        v-if="todoToDelete"
        :todo="todoToDelete"
        @confirm="confirmDelete"
        @cancel="cancelDelete"
    />

    <CreateTodoModal
      v-if="showCreateTodoModal && selectedDate"
      :date="selectedDate"
      @save="saveNewTodoFromModal"
      @cancel="closeCreateTodoModal"
    />
  </template>

<script>
import { defineAsyncComponent } from 'vue';
import './todo-app.css';

import DeleteConfirmModal from './shared/DeleteConfirmModal.vue';
import TodoFilter from './todos/TodoFilter.vue';
import TodoForm from './todos/TodoForm.vue';
import TodoList from './todos/TodoList.vue';
import TodoEditModal from './todos/TodoEditModal.vue';
import AlertError from './shared/AlertError.vue';
import AlertSuccess from './shared/AlertSuccess.vue';
import TodoCalendar from './todos/TodoCalendar.vue';
import TodoViewSelector from './todos/TodoViewSelector.vue';

export default {
  components: {
    TodoFilter,
    TodoForm,
    TodoList,
    TodoEditModal,
    AlertError,
    AlertSuccess,
    DeleteConfirmModal,
    TodoCalendar,
    TodoViewSelector,
    CreateTodoModal: defineAsyncComponent(() => import('./todos/CreateTodoModal.vue'))
},

  data() {
    const today = new Date();
    const formattedToday = this.formatDateForInput(today);

    return {
      todos: [],
      loading: true,
      error: null,
      successMessage: null,
      newTodo: {
        title: '',
        description: '',
        completed: false,
        due_date: formattedToday,
        reminder_at: null
      },
      editingTodo: null,
      searchQuery: '',
      filterStatus: 'all',
      todoToDelete: null,
      currentView: 'list',
      alwaysShowCalendar: true,
      showCreateTodoModal: false,
      selectedDate: null,
      calendarRefreshKey: 0
    }
  },

  computed: {
    filteredTodos() {
      return this.todos.filter(todo => {
        if (this.filterStatus === 'active' && todo.completed) return false;
        if (this.filterStatus === 'completed' && !todo.completed) return false;
        if (this.filterStatus === 'due-today' && !this.isDueToday(todo.due_date)) return false;
        if (this.filterStatus === 'overdue' && !this.isOverdue(todo.due_date)) return false;

        if (this.searchQuery) {
          const query = this.searchQuery.toLowerCase();
          return todo.title.toLowerCase().includes(query) ||
                 (todo.description && todo.description.toLowerCase().includes(query));
        }

        return true;
      });
    }
  },

  mounted() {
    this.fetchTodos();
  },

  methods: {
    setView(view) {
      this.currentView = view;
    },

    refreshCalendar() {
      this.calendarRefreshKey++;
    },

    openCreateTodoModal(dateString) {
      this.selectedDate = dateString;
      this.showCreateTodoModal = true;
    },

    closeCreateTodoModal() {
      this.showCreateTodoModal = false;
      this.selectedDate = null;
    },

    async saveNewTodoFromModal(newTodo) {
      await this.addTodo(newTodo);
      this.closeCreateTodoModal();
    },

    formatDateForInput(date) {
      if (!date) return null;

      const d = new Date(date);
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');

      return `${year}-${month}-${day}`;
    },

    formatDateTimeForInput(date) {
      if (!date) return null;

      const d = new Date(date);
      const year = d.getFullYear();
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      const hours = String(d.getHours()).padStart(2, '0');
      const minutes = String(d.getMinutes()).padStart(2, '0');

      return `${year}-${month}-${day}T${hours}:${minutes}`;
    },

    async fetchTodos() {
      try {
        this.loading = true;
        this.error = null;

        const response = await fetch('/api/todos');

        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        this.todos = data;
      } catch (error) {
        console.error('Erreur lors du chargement des tâches:', error);
        this.error = `Impossible de charger les tâches: ${error.message}`;
      } finally {
        this.loading = false;
      }
    },

    async addTodo(newTodo) {
      try {
        this.error = null;
        const todoToSend = { ...newTodo };

        const response = await fetch('/api/todos', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(todoToSend)
        });

        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

        const todo = await response.json();

        this.todos = [todo, ...this.todos];

        this.refreshCalendar();

        const today = new Date();
        this.newTodo = {
          title: '',
          description: '',
          completed: false,
          due_date: this.formatDateForInput(today),
          reminder_at: null
        };

        this.showSuccess('Tâche ajoutée avec succès');
      } catch (error) {
        console.error('Erreur lors de l\'ajout de la tâche:', error);
        this.error = `Impossible d'ajouter la tâche: ${error.message}`;
      }
    },

    async toggleComplete(todo) {
      try {
        this.error = null;

        const todoToUpdate = { ...todo };
        const newCompleted = !todo.completed;

        const index = this.todos.findIndex(t => t.id === todo.id);
        if (index !== -1) {
          const updatedTodo = { ...this.todos[index], completed: newCompleted };

          this.todos = [
            ...this.todos.slice(0, index),
            updatedTodo,
            ...this.todos.slice(index + 1)
          ];
        }

        this.refreshCalendar();

        const response = await fetch(`/api/todos/${todo.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            completed: newCompleted
          })
        });

        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

        const updatedTodoFromServer = await response.json();

        const finalIndex = this.todos.findIndex(t => t.id === updatedTodoFromServer.id);
        if (finalIndex !== -1) {
          this.todos = [
            ...this.todos.slice(0, finalIndex),
            updatedTodoFromServer,
            ...this.todos.slice(finalIndex + 1)
          ];
        }

        this.showSuccess(updatedTodoFromServer.completed ? 'Tâche marquée comme terminée' : 'Tâche marquée comme en cours');
      } catch (error) {
        console.error('Erreur lors de la mise à jour de la tâche:', error);
        this.error = `Impossible de mettre à jour la tâche: ${error.message}`;
        this.fetchTodos();
      }
    },

    editTodo(todo) {
      const todoForEdit = JSON.parse(JSON.stringify(todo));

      if (todoForEdit.due_date) {
        todoForEdit.due_date = this.formatDateForInput(new Date(todoForEdit.due_date));
      }

      if (todoForEdit.reminder_at) {
        todoForEdit.reminder_at = this.formatDateTimeForInput(new Date(todoForEdit.reminder_at));
      }

      this.editingTodo = todoForEdit;
    },

    cancelEdit() {
      this.editingTodo = null;
    },

    async saveEdit(updatedTodo) {
      try {
        this.error = null;

        // N'appliquer le changement qu'APRÈS confirmation du modal
        const response = await fetch(`/api/todos/${updatedTodo.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            title: updatedTodo.title,
            description: updatedTodo.description,
            completed: updatedTodo.completed,
            due_date: updatedTodo.due_date,
            reminder_at: updatedTodo.reminder_at
          })
        });

        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

        const todoFromServer = await response.json();

        // Mettre à jour APRÈS confirmation du serveur
        const index = this.todos.findIndex(t => t.id === todoFromServer.id);
        if (index !== -1) {
          this.todos = [
            ...this.todos.slice(0, index),
            todoFromServer,
            ...this.todos.slice(index + 1)
          ];
        }

        // Rafraîchir le calendrier après la modification
        this.refreshCalendar();

        this.editingTodo = null;
        this.showSuccess('Tâche mise à jour avec succès');
      } catch (error) {
        console.error('Erreur lors de la mise à jour de la tâche:', error);
        this.error = `Impossible de mettre à jour la tâche: ${error.message}`;
        // En cas d'erreur, recharger les todos
        this.fetchTodos();
      }
    },

    // Méthode de suppression qui ouvre le modal SANS supprimer la tâche
    deleteTodo(todo) {
      this.todoToDelete = todo;
    },

    // Méthode pour annuler la suppression
    cancelDelete() {
      this.todoToDelete = null;
    },

    // Méthode pour confirmer la suppression - ne supprimer qu'APRÈS confirmation
    async confirmDelete() {
    try {
        this.error = null;
        const todoId = this.todoToDelete.id;

        const response = await fetch(`/api/todos/${todoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
        });

        if (!response.ok) {
        throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

        this.todos = this.todos.filter(t => t.id !== todoId);

        // Rafraîchir le calendrier après la suppression
        this.refreshCalendar();

        this.showSuccess('Tâche supprimée avec succès');
        this.todoToDelete = null;
    } catch (error) {
        console.error('Erreur lors de la suppression de la tâche:', error);
        this.error = `Impossible de supprimer la tâche: ${error.message}`;
        this.fetchTodos();
    }
    },

    isOverdue(dateString) {
      if (!dateString) return false;

      const dueDate = new Date(dateString);
      const today = new Date();

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
    },

    showSuccess(message) {
        this.successMessage = message;
    },

    getEmptyStateMessage() {
      if (this.searchQuery) {
        return 'Aucune tâche ne correspond à votre recherche.';
      }

      switch (this.filterStatus) {
        case 'active': return 'Aucune tâche en cours.';
        case 'completed': return 'Aucune tâche terminée.';
        case 'due-today': return 'Aucune tâche à faire aujourd\'hui.';
        case 'overdue': return 'Aucune tâche en retard.';
        default: return 'Ajoutez une tâche pour commencer.';
      }
    }
  }
}
</script>
