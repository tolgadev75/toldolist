<template>
    <div class="w-full">
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
        :new-todo="newTodo"
        @add-todo="addTodo"
      />

      <!-- État de chargement -->
      <div v-if="loading" class="flex justify-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>

      <!-- Message si aucune tâche -->
      <div v-else-if="filteredTodos.length === 0" class="bg-white rounded-xl shadow-sm p-8 text-center">
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

      <!-- Liste des tâches -->
      <TodoList
        v-else
        :todos="filteredTodos"
        :filter-status="filterStatus"
        @toggle-complete="toggleComplete"
        @edit-todo="editTodo"
        @delete-todo="deleteTodo"
      />

      <!-- Modal d'édition -->
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
        @cancel="todoToDelete = null"
    />
  </template>

<script>
import DeleteConfirmModal from './shared/DeleteConfirmModal.vue';
import TodoFilter from './todos/TodoFilter.vue';
import TodoForm from './todos/TodoForm.vue';
import TodoList from './todos/TodoList.vue';
import TodoEditModal from './todos/TodoEditModal.vue';
import AlertError from './shared/AlertError.vue';
import AlertSuccess from './shared/AlertSuccess.vue';

export default {
  components: {
    TodoFilter,
    TodoForm,
    TodoList,
    TodoEditModal,
    AlertError,
    AlertSuccess,
    DeleteConfirmModal
  },

  data() {
    // Initialiser la date du jour pour les nouvelles tâches
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
      todoToDelete: null
    }
  },

  computed: {
    filteredTodos() {
      return this.todos.filter(todo => {
        // Filtre par statut
        if (this.filterStatus === 'active' && todo.completed) return false;
        if (this.filterStatus === 'completed' && !todo.completed) return false;
        if (this.filterStatus === 'due-today' && !this.isDueToday(todo.due_date)) return false;
        if (this.filterStatus === 'overdue' && !this.isOverdue(todo.due_date)) return false;

        // Filtre par recherche
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
        this.todos.unshift(todo);

        // Réinitialiser le formulaire mais garder la date du jour par défaut
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
        const response = await fetch(`/api/todos/${todo.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            completed: !todo.completed
          })
        });

        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

        const updatedTodo = await response.json();
        // Mettre à jour la tâche dans la liste
        const index = this.todos.findIndex(t => t.id === updatedTodo.id);
        if (index !== -1) {
          this.todos[index] = updatedTodo;
        }

        this.showSuccess(updatedTodo.completed ? 'Tâche marquée comme terminée' : 'Tâche marquée comme en cours');
      } catch (error) {
        console.error('Erreur lors de la mise à jour de la tâche:', error);
        this.error = `Impossible de mettre à jour la tâche: ${error.message}`;
      }
    },

    editTodo(todo) {
      // Créer une copie pour l'édition
      const todoForEdit = JSON.parse(JSON.stringify(todo));

      // Formater la date d'échéance pour le champ input type="date"
      if (todoForEdit.due_date) {
        todoForEdit.due_date = this.formatDateForInput(new Date(todoForEdit.due_date));
      }

      // Formater la date de rappel pour le champ input type="datetime-local"
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

        // Mettre à jour l'objet dans le tableau
        const index = this.todos.findIndex(t => t.id === todoFromServer.id);
        if (index !== -1) {
          this.todos[index] = todoFromServer;
        }

        this.editingTodo = null;
        this.showSuccess('Tâche mise à jour avec succès');
      } catch (error) {
        console.error('Erreur lors de la mise à jour de la tâche:', error);
        this.error = `Impossible de mettre à jour la tâche: ${error.message}`;
      }
    },

    // Nouvelle méthode de suppression qui ouvre le modal
    deleteTodo(todo) {
      this.todoToDelete = todo;
    },

    // Nouvelle méthode pour annuler la suppression
    cancelDelete() {
      this.todoToDelete = null;
    },

    // Nouvelle méthode pour confirmer la suppression
    async confirmDelete() {
      try {
        this.error = null;
        const response = await fetch(`/api/todos/${this.todoToDelete.id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });

        if (!response.ok) {
          throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }

        const index = this.todos.findIndex(t => t.id === this.todoToDelete.id);
        if (index !== -1) {
          this.todos.splice(index, 1);
        }

        this.showSuccess('Tâche supprimée avec succès');
        this.todoToDelete = null;
      } catch (error) {
        console.error('Erreur lors de la suppression de la tâche:', error);
        this.error = `Impossible de supprimer la tâche: ${error.message}`;
      }
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
