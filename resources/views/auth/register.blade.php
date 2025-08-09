@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Inscription</h2>
    <form id="register-form">
        <input name="nom" type="text" placeholder="Nom" class="w-full border mb-2 p-2 rounded" required>
        <input name="email" type="email" placeholder="Email" class="w-full border mb-2 p-2 rounded" required>
        <input name="password" type="password" placeholder="Mot de passe" class="w-full border mb-2 p-2 rounded" required>
        <input name="password_confirmation" type="password" placeholder="Confirmer le mot de passe" class="w-full border mb-2 p-2 rounded" required>
        <input name="role_id" type="number" class="w-full border mb-2 p-2 rounded" required>
        <button class="w-full bg-blue-600 text-white py-2 rounded">S'inscrire</button>
    </form>
    <div id="register-error" class="text-red-600 mt-2"></div>
</div>
<script>
document.getElementById('register-form').onsubmit = function(e) {
    e.preventDefault();
    fetch('/api/register', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({
            nom: this.nom.value,
            email: this.email.value,
            password: this.password.value,
            password_confirmation: this.password_confirmation.value,
            role_id:this.role_id.value,
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.token) {
            localStorage.setItem('token', data.token);
            window.location = "{{ route('dashboard') }}";
        } else {
            document.getElementById('register-error').innerText = data.message || 'Erreur lors de l’inscription';
        }
    });
}
</script>
@endsection