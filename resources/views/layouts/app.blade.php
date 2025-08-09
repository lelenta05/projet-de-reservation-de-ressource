<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Accueil')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 p-4 text-white flex justify-between">
        <div>
            <a href="{{ route('home') }}" class="font-bold mr-6">Accueil</a>
                       
            <a href="{{ route('ressources.index') }}" class="mr-4">Ressources</a>
            <a href="{{ route('reservations.index') }}" class="mr-4">Réservations</a>
        </div>
        <div>
            @auth
                <span class="mr-4">👤 {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="hover:underline">Déconnexion</button>
                </form>
                 <a href="{{ route('dashboard') }}" class="font-bold mr-6">Accueil</a>
            @else
                <a href="{{ route('login') }}" class="hover:underline mr-4">Connexion</a>
                <a href="{{ route('register') }}" class="hover:underline">Inscription</a>
            @endauth
        </div>
    </nav>
    <main class="max-w-4xl mx-auto mt-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-200 text-red-800 rounded">{{ session('error') }}</div>
        @endif
        @yield('content')
    <script>
    // Simple nav dynamique selon le token
    if(localStorage.getItem('token')){
        document.getElementById('nav-auth').innerHTML = `
            <button onclick="logout()" class="hover:underline">Déconnexion</button>
        `;
    }else{
        document.getElementById('nav-auth').innerHTML = `
            <a href="{{ route('login') }}" class="hover:underline mr-2">Connexion</a>
            <a href="{{ route('register') }}" class="hover:underline">Inscription</a>
        `;
    }
    function logout() {
        fetch('/api/logout', {
            method: 'POST',
            headers: { "Authorization": "Bearer " + localStorage.getItem('token') }
        }).then(()=>{
            localStorage.removeItem('token');
            window.location = "{{ route('home') }}";
        });
    }
    </script>
</body>
</html>