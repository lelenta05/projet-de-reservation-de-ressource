@extends('layouts.app')
@section('title', 'Accueil')

@section('content')
<div class="text-center mt-20">
    <h1 class="text-4xl font-bold mb-8">Bienvenue sur la plateforme de reservation de ressources </h1>
    <div class="space-x-4">
        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded">Connexion</a>
        <a href="{{ route('register') }}" class="bg-green-600 text-white px-6 py-3 rounded">Inscription</a>
    </div>
</div>
@endsection