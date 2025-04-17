@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-12 md:py-20 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Organisez votre vie avec ToLDoList</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
            Une application simple et intuitive pour gérer vos tâches quotidiennes et rester productif.
        </p>
        <div>
            <a href="/todos" class="inline-block px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                Commencer maintenant
            </a>
        </div>
    </div>

    <div class="py-12 bg-white rounded-lg shadow-sm">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Fonctionnalités</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="p-6 text-center">
                <div class="inline-flex items-center justify-center p-3 bg-blue-100 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Gestion simple</h3>
                <p class="text-gray-600">Ajoutez, modifiez et supprimez facilement vos tâches en quelques clics.</p>
            </div>

            <div class="p-6 text-center">
                <div class="inline-flex items-center justify-center p-3 bg-blue-100 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Marquez comme terminé</h3>
                <p class="text-gray-600">Suivez votre progression en marquant les tâches comme complétées.</p>
            </div>

            <div class="p-6 text-center">
                <div class="inline-flex items-center justify-center p-3 bg-blue-100 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Organisation efficace</h3>
                <p class="text-gray-600">Organisez vos tâches avec des descriptions détaillées.</p>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Comment ça marche</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="text-3xl font-bold text-blue-600 mb-4">1</div>
                <h3 class="text-xl font-medium mb-2">Créez votre compte</h3>
                <p class="text-gray-600">Inscrivez-vous gratuitement pour commencer à utiliser ToLDoList.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="text-3xl font-bold text-blue-600 mb-4">2</div>
                <h3 class="text-xl font-medium mb-2">Ajoutez vos tâches</h3>
                <p class="text-gray-600">Créez des tâches avec titres et descriptions détaillées.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="text-3xl font-bold text-blue-600 mb-4">3</div>
                <h3 class="text-xl font-medium mb-2">Gérez votre progression</h3>
                <p class="text-gray-600">Suivez votre productivité en cochant les tâches terminées.</p>
            </div>
        </div>
    </div>
</div>
@endsection
