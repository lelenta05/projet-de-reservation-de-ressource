@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto mt-8 px-4">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Les réservations de la plateforme</h2>

    <div id="flash-message" class="hidden mb-4 p-3 rounded"></div>

    <div id="reservations-list" class="space-y-4"></div>

    <div class="mt-6">
        <button id="open-create" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Nouvelle réservation
        </button>
    </div>

    <div id="reservation-form" class="mt-6"></div>
</div>

<script>
let token = localStorage.getItem('token') || '';

// === Helpers ===
function escapeHtml(str) {
    return String(str || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
}

function showFlash(message, isError = false) {
    const flash = document.getElementById('flash-message');
    flash.innerText = message;
    flash.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
    if (isError) {
        flash.classList.add('bg-red-100', 'text-red-700');
    } else {
        flash.classList.add('bg-green-100', 'text-green-700');
    }
    window.clearTimeout(flash._timeout);
    flash._timeout = setTimeout(() => flash.classList.add('hidden'), 5000);
}

// Convertit une date (ex: "2025-08-09 14:30:00" ou ISO) en "YYYY-MM-DDTHH:mm" pour datetime-local
function toDatetimeLocal(value) {
    if (!value) return '';
    // si ISO déjà avec T
    if (value.includes('T')) {
        const d = new Date(value);
        if (isNaN(d)) return value.slice(0,16);
        return d.toISOString().slice(0,16);
    }
    // remplacer espace par T
    const normalized = value.replace(' ', 'T');
    // essayer Date
    const d = new Date(normalized);
    if (!isNaN(d)) return d.toISOString().slice(0,16);
    // fallback: cut first 16 chars
    return normalized.slice(0,16);
}

// === Fetch & render reservations ===
async function fetchReservations() {
    try {
        const res = await fetch('/api/reservations', {
            headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
        });
        if (!res.ok) {
            showFlash('Erreur chargement réservations (' + res.status + ')', true);
            return;
        }
        const data = await res.json();
        renderReservationList(data);
    } catch (err) {
        console.error('fetchReservations error:', err);
        showFlash('Erreur réseau lors du chargement des réservations', true);
    }
}

function renderReservationList(list) {
    if (!Array.isArray(list) || list.length === 0) {
        document.getElementById('reservations-list').innerHTML = `
            <div class="text-gray-500 italic">Aucune réservation pour le moment.</div>
        `;
        return;
    }

    const html = list.map(r => `
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div class="mb-3 sm:mb-0">
                <div class="text-gray-800 font-medium">${escapeHtml(r.ressource_nom ?? ('Ressource #' + r.ressource_id))}</div>
                <div class="text-sm text-gray-600 mt-1">
                    <div><span class="font-semibold">Début :</span> ${escapeHtml(r.date_debut)}</div>
                    <div><span class="font-semibold">Fin :</span> ${escapeHtml(r.date_fin)}</div>
                    <div><span class="font-semibold">Statut :</span> <span class="font-semibold">${escapeHtml(r.statut)}</span></div>
                </div>
            </div>

            <div class="flex gap-2">
                <button data-id="${r.id}" class="delete-reservation bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Supprimer</button>
                <button data-id="${r.id}" class="edit-reservation bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Éditer</button>
            </div>
        </div>
    `).join('');

    document.getElementById('reservations-list').innerHTML = html;
}

// === Open create form (charge la liste des ressources pour le select) ===
document.getElementById('open-create').addEventListener('click', showReservationForm);

async function showReservationForm() {
    try {
        const res = await fetch('/api/ressources', {
            headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
        });
        if (!res.ok) {
            showFlash('Impossible de charger les ressources ('+res.status+')', true);
            return;
        }
        const ressources = await res.json();
        const options = ressources.map(r => `<option value="${r.id}">${escapeHtml(r.nom)}</option>`).join('');

        document.getElementById('reservation-form').innerHTML = `
            <form id="create-reservation" class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm space-y-3">
                <select name="ressource_id" class="border p-2 rounded w-full" required>
                    <option value="">-- Sélectionner une ressource --</option>
                    ${options}
                </select>

                <input name="date_debut" type="datetime-local" class="w-full border p-2 rounded" required>
                <input name="date_fin" type="datetime-local" class="w-full border p-2 rounded" required>

                <input name="statut" type="hidden" value="pending">

                <div class="flex gap-2">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Réserver</button>
                    <button type="button" id="cancel-create" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Annuler</button>
                </div>
            </form>
        `;

        // attach handlers
        const form = document.getElementById('create-reservation');
        form.addEventListener('submit', createReservation);
        document.getElementById('cancel-create').addEventListener('click', () => {
            document.getElementById('reservation-form').innerHTML = '';
        });
    } catch (err) {
        console.error('showReservationForm error:', err);
        showFlash('Erreur réseau lors du chargement des ressources', true);
    }
}

// === Create reservation ===
async function createReservation(e) {
    e.preventDefault();
    const form = e.target;
    const payload = {
        ressource_id: form.ressource_id.value,
        date_debut: form.date_debut.value,
        date_fin: form.date_fin.value,
        statut: 'pending'
    };

    try {
        const res = await fetch('/api/reservations', {
            method: 'POST',
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const text = await res.text();
        let data;
        try { data = text ? JSON.parse(text) : null; } catch { data = text; }

        console.log('create reservation', res.status, data);

        if (!res.ok) {
            if (res.status === 422 && data && data.errors) {
                const key = Object.keys(data.errors)[0];
                showFlash(data.errors[key][0] || 'Erreur validation', true);
                return;
            }
            showFlash(data?.message || 'Erreur lors de la création', true);
            return;
        }

        showFlash('Réservation créée ! Un email sera envoyé lors de la validation/refus.');
        form.reset();
        document.getElementById('reservation-form').innerHTML = '';
        await fetchReservations();
    } catch (err) {
        console.error('createReservation error:', err);
        showFlash('Erreur réseau lors de la création', true);
    }
}

// === Delete reservation ===
async function deleteReservation(id) {
    if (!confirm('Supprimer cette réservation ?')) return;
    try {
        const res = await fetch('/api/reservations/' + id, {
            method: 'DELETE',
            headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
        });
        if (!res.ok) {
            const txt = await res.text();
            showFlash('Erreur suppression: ' + (txt || res.status), true);
            return;
        }
        showFlash('Réservation supprimée.');
        await fetchReservations();
    } catch (err) {
        console.error('deleteReservation error:', err);
        showFlash('Erreur réseau lors de la suppression', true);
    }
}

// === Edit flow: load reservation, show form, submit update ===
async function loadReservation(id) {
    try {
        const res = await fetch('/api/reservations/' + id, {
            headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
        });
        if (!res.ok) {
            showFlash('Impossible de charger la réservation ('+res.status+')', true);
            return null;
        }
        return await res.json();
    } catch (err) {
        console.error('loadReservation error:', err);
        showFlash('Erreur réseau lors du chargement', true);
        return null;
    }
}

async function showEditReservationForm(id) {
    // charger la réservation + les ressources pour le select
    const [reservation, resRessources] = await Promise.all([
        loadReservation(id),
        fetch('/api/ressources', { headers: { "Authorization": "Bearer " + token, "Accept": "application/json" } }).then(r => r.ok ? r.json() : [])
    ]);

    if (!reservation) return;

    const options = (resRessources || []).map(r => `<option value="${r.id}" ${r.id == reservation.ressource_id ? 'selected' : ''}>${escapeHtml(r.nom)}</option>`).join('');

    document.getElementById('reservation-form').innerHTML = `
        <form id="edit-reservation" class="bg-yellow-50 p-4 rounded-lg border border-gray-200 shadow-sm space-y-3">
            <select name="ressource_id" class="border p-2 rounded w-full" required>
                <option value="">-- Sélectionner une ressource --</option>
                ${options}
            </select>

            <input name="date_debut" type="datetime-local" class="w-full border p-2 rounded" value="${toDatetimeLocal(reservation.date_debut)}" required>
            <input name="date_fin" type="datetime-local" class="w-full border p-2 rounded" value="${toDatetimeLocal(reservation.date_fin)}" required>

            <select name="statut" class="w-full border p-2 rounded">
                <option value="pending" ${reservation.statut === 'pending' ? 'selected' : ''}>En attente</option>
                <option value="approved" ${reservation.statut === 'approved' ? 'selected' : ''}>Validée</option>
                <option value="rejected" ${reservation.statut === 'rejected' ? 'selected' : ''}>Refusée</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">Modifier</button>
                <button type="button" id="cancel-edit" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Annuler</button>
            </div>
        </form>
    `;

    // attach handlers
    document.getElementById('edit-reservation').addEventListener('submit', function(e){
        submitEditReservation(e, id);
    });
    document.getElementById('cancel-edit').addEventListener('click', () => {
        document.getElementById('reservation-form').innerHTML = '';
    });
}

async function submitEditReservation(e, id) {
    e.preventDefault();
    const form = e.target;
    const payload = {
        ressource_id: form.ressource_id.value,
        date_debut: form.date_debut.value,
        date_fin: form.date_fin.value,
        statut: form.statut.value
    };

    try {
        const res = await fetch('/api/reservations/' + id, {
            method: 'PUT',
            headers: {
                "Authorization": "Bearer " + token,
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const text = await res.text();
        let data;
        try { data = text ? JSON.parse(text) : null; } catch { data = text; }

        if (!res.ok) {
            if (res.status === 422 && data && data.errors) {
                const key = Object.keys(data.errors)[0];
                showFlash(data.errors[key][0] || 'Erreur de validation', true);
                return;
            }
            showFlash(data?.message || 'Erreur lors de la modification', true);
            return;
        }

        showFlash('Réservation modifiée. Un e-mail sera envoyé si le statut change.');
        document.getElementById('reservation-form').innerHTML = '';
        await fetchReservations();
    } catch (err) {
        console.error('submitEditReservation error:', err);
        showFlash('Erreur réseau lors de la modification', true);
    }
}

// === Event delegation for edit/delete buttons ===
document.getElementById('reservations-list').addEventListener('click', function(e) {
    const del = e.target.closest('.delete-reservation');
    if (del) {
        const id = del.getAttribute('data-id');
        deleteReservation(id);
        return;
    }
    const edit = e.target.closest('.edit-reservation');
    if (edit) {
        const id = edit.getAttribute('data-id');
        showEditReservationForm(id);
        return;
    }
});

// initial load
fetchReservations();
</script>
@endsection
