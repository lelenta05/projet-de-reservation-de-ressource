@extends('layouts.app')
@section('content')
<script>
if (!localStorage.getItem('token')) {
    window.location = "{{ route('login') }}";
}
</script>
<div class="text-center mt-10">
    <h1 class="text-3xl font-bold mb-6">Bienvenue sur la plateforme de reservation de ressources !</h1>
    <div class="space-x-4">
        <a href="{{ route('ressources.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Ressources</a>
        <a href="{{ route('reservations.index') }}" class="bg-green-600 text-white px-4 py-2 rounded">Réservations</a>
    </div>
</div>
@endsection