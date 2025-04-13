<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Afficher toutes les tâches du user connecté.
     */
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json($todos);
    }

    /**
     * save une nouvelle tache todo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
            'due_date' => 'nullable|date',
            'reminder_at' => 'nullable|date',
        ]);

        // Ajouter id de l'utilisateur connecté
        $validated['user_id'] = Auth::id();

        $todo = Todo::create($validated);

        return response()->json($todo, 201);
    }

    /**
     * Afficher une tâche spécifique.
     */
    public function show(Todo $todo)
    {
        // ici on vérifie que la tâche appartient à l'user connecté
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        return response()->json($todo);
    }

    /**
     * Mettre à jour une tâche existante.
     */
    public function update(Request $request, Todo $todo)
    {
        // Vérifier que la tâche appartient a l'user connecté
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
            'due_date' => 'nullable|date',
            'reminder_at' => 'nullable|date',
        ]);

        $todo->update($validated);

        return response()->json($todo);
    }

    /**
     * Supprimer une tâche.
     */
    public function destroy(Todo $todo)
    {
        // Vérifier que la tâche appartient à l'utilisateur connecté
        if ($todo->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $todo->delete();

        return response()->json(null, 204);
    }
}
