@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow text-center">
    <h2 class="text-xl font-bold mb-4">Déconnexion</h2>
    <button id="logout-btn" class="bg-red-600 text-white px-4 py-2 rounded">Se déconnecter</button>
    <div id="logout-success" class="text-green-600 mt-2"></div>
</div>
<script>
document.getElementById('logout-btn').onclick = function() {
    let token = localStorage.getItem('token');
    fetch('/api/logout', {
        method: 'POST',
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    })
    .then(res => {
        localStorage.removeItem('token');
        document.getElementById('logout-success').innerText = "Déconnexion réussie.";
        setTimeout(() => window.location = "{{ route('login') }}", 1500);
    });
}
</script>
@endsection