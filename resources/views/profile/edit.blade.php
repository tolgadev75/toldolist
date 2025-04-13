@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mon Profil
            </h2>
        </div>

        <!-- Alertes -->
        @if(session('success'))
            <x-alert type="success" message="{{ session('success') }}" />
        @endif

        @if(session('error'))
            <x-alert type="error" message="{{ session('error') }}" />
        @endif

        <div class="space-y-6">
            <!-- Formulaire de mise à jour des informations du profil -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations du profil</h3>

                    <form method="post" action="{{ route('profile.update') }}" class="max-w-xl">
                        @csrf
                        @method('patch')

                        <div class="grid gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required autofocus
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Formulaire de mise à jour du mot de passe -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier le mot de passe</h3>

                    <form method="post" action="{{ route('profile.password.update') }}" class="max-w-xl">
                        @csrf
                        @method('put')

                        <div class="grid gap-6">
                            <!-- Mot de passe actuel -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                                <input type="password" name="current_password" id="current_password" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('current_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nouveau mot de passe -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Le mot de passe doit contenir au moins 8 caractères, des lettres et des chiffres.</p>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Mettre à jour le mot de passe
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Formulaire de suppression du compte -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Supprimer le compte</h3>

                    <p class="text-sm text-gray-600 mb-4">
                        Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
                        Avant de supprimer votre compte, veuillez télécharger les données que vous souhaitez conserver.
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="max-w-xl">
                        @csrf
                        @method('delete')

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                            <input type="password" name="password" id="password" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Supprimer le compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-close alerts after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.top-5.right-5');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }, 3000);
    });
</script>
@endsection
