@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Connexion</h2>
    <form id="login-form">
        <input name="email" type="email" placeholder="Email" class="w-full border mb-2 p-2 rounded" required>
        <input name="password" type="password" placeholder="Mot de passe" class="w-full border mb-2 p-2 rounded" required>
        <button class="w-full bg-blue-600 text-white py-2 rounded">Connexion</button>
    </form>
    <div id="login-error" class="text-red-600 mt-2"></div>
</div>
<script>
document.getElementById('login-form').onsubmit = function(e) {
    e.preventDefault();
    fetch('/api/login', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({
            email: this.email.value,
            password: this.password.value
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.token) {
            localStorage.setItem('token', data.token);
            window.location = "{{ route('dashboard') }}";
        } else {
            document.getElementById('login-error').innerText = data.message || 'Erreur de connexion';
        }
    });
}
</script>
@endsection