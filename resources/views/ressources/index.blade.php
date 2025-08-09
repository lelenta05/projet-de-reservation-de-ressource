@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8 px-4">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Liste des ressources</h2>
    
    <div id="flash-message" class="hidden p-3 mb-4 rounded bg-green-100 text-green-700"></div>
    
    <div id="ressources-list" class="space-y-4"></div>
    
    <div class="mt-6">
        <button onclick="showCreateForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md">
            Ajouter une ressource
        </button>
    </div>
    
    <div id="ressource-form" class="mt-6"></div>
</div>

<script>
let token = localStorage.getItem('token') || '';

// helper escape
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
    flash.classList.remove('hidden');
    flash.classList.toggle('bg-green-100', !isError);
    flash.classList.toggle('text-green-700', !isError);
    flash.classList.toggle('bg-red-100', isError);
    flash.classList.toggle('text-red-700', isError);
    setTimeout(() => flash.classList.add('hidden'), 4000);
}

// --- FETCH + RENDER ressources ---
async function fetchRessources() {
    try {
        const res = await fetch('/api/ressources', {
            headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
        });
        if (!res.ok) {
            showFlash('Erreur chargement ressources ('+res.status+')', true);
            return;
        }
        const data = await res.json();
        let html = '';
        if (!Array.isArray(data) || data.length === 0) {
            html = `<div class="text-gray-500 italic">Aucune ressource disponible.</div>`;
        } else {
            data.forEach(r => {
                html += `
                    <div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-white flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">${escapeHtml(r.nom)}</h3>
                            <p class="text-gray-600">${escapeHtml(r.description || '')}</p>
                            <div class="text-sm text-gray-500 mt-1">
                                Localisation : ${escapeHtml(r.localisation || '')} | Type : ${escapeHtml(r.type || '')} | Capacité : ${escapeHtml(String(r.capacite || ''))}
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button data-id="${r.id}" class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Supprimer</button>
                            <button data-id="${r.id}" class="edit-btn bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Éditer</button>
                        </div>
                    </div>
                `;
            });
        }
        document.getElementById('ressources-list').innerHTML = html;
    } catch (err) {
        console.error('fetchRessources error:', err);
        showFlash('Erreur réseau lors du chargement', true);
    }
}

// --- SHOW create form and attach submit handler ---
function showCreateForm() {
    document.getElementById('ressource-form').innerHTML = `
        <form id="create-form" class="bg-gray-50 p-4 rounded-lg shadow space-y-3">
            <input name="nom" type="text" placeholder="Nom" class="border p-2 rounded w-full" required>
            <input name="description" type="text" placeholder="Description" class="border p-2 rounded w-full">
            <input name="localisation" type="text" placeholder="Localisation" class="border p-2 rounded w-full" required>
            <input name="type" type="text" placeholder="Type" class="border p-2 rounded w-full" required>
            <input name="capacite" type="number" placeholder="Capacité" class="border p-2 rounded w-full" required>
            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Créer</button>
                <button type="button" id="cancel-create" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Annuler</button>
            </div>
        </form>
    `;

    // attach handler now that form exists
    const createForm = document.getElementById('create-form');
    if (createForm) {
        createForm.addEventListener('submit', createRessource);
    }

    const cancelBtn = document.getElementById('cancel-create');
    if (cancelBtn) cancelBtn.addEventListener('click', () => {
        document.getElementById('ressource-form').innerHTML = '';
    });
}

// --- CREATE ---
async function createRessource(e) {
    e.preventDefault();
    const form = e.target;
    const payload = {
        nom: form.nom.value,
        description: form.description.value,
        localisation: form.localisation.value,
        type: form.type.value,
        capacite: form.capacite.value
    };

    try {
        const res = await fetch('/api/ressources', {
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

        console.log('createRessource', res.status, data);

        if (!res.ok) {
            if (res.status === 422 && data && data.errors) {
                const key = Object.keys(data.errors)[0];
                showFlash(data.errors[key][0] || 'Erreur de validation', true);
                return;
            }
            if (res.status === 401) { showFlash('Non authentifié', true); return; }
            showFlash(data?.message || `Erreur (${res.status})`, true);
            return;
        }

        showFlash('Ressource créée avec succès.');
        form.reset();
        // recharge la liste depuis le serveur pour inclure la nouvelle ressource
        await fetchRessources();
        // vider le formulaire
        document.getElementById('ressource-form').innerHTML = '';
    } catch (err) {
        console.error('createRessource error:', err);
        showFlash('Erreur réseau lors de la création', true);
    }
}

// --- DELETE ---
async function deleteRessource(id) {
    if (!confirm('Supprimer cette ressource ?')) return;
    try {
        const res = await fetch('/api/ressources/' + id, {
            method: 'DELETE',
            headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
        });
        if (!res.ok) {
            const t = await res.text();
            showFlash('Erreur suppression: ' + (t || res.status), true);
            return;
        }
        showFlash('Ressource supprimée.');
        await fetchRessources();
    } catch (err) {
        console.error('deleteRessource error:', err);
        showFlash('Erreur réseau lors de la suppression', true);
    }
}

// --- EDIT FLOW: load resource and show form ---
async function loadRessourceAndShowForm(id) {
    try {
        const res = await fetch('/api/ressources/' + id, {
            headers: { "Authorization": "Bearer " + token, "Accept": "application/json" }
        });
        if (!res.ok) { showFlash('Impossible de charger la ressource', true); return; }
        const item = await res.json();
        showEditForm(item);
    } catch (err) {
        console.error('loadRessource error:', err);
        showFlash('Erreur réseau lors du chargement', true);
    }
}

function showEditForm(item) {
    const { id, nom = '', description = '', localisation = '', type = '', capacite = '' } = item || {};
    document.getElementById('ressource-form').innerHTML = `
        <form id="edit-form" class="bg-yellow-50 p-4 rounded-lg shadow space-y-3">
            <input name="nom" type="text" value="${escapeHtml(nom)}" class="border p-2 rounded w-full" required>
            <input name="description" type="text" value="${escapeHtml(description)}" class="border p-2 rounded w-full">
            <input name="localisation" type="text" value="${escapeHtml(localisation)}" class="border p-2 rounded w-full" required>
            <input name="type" type="text" value="${escapeHtml(type)}" class="border p-2 rounded w-full" required>
            <input name="capacite" type="number" value="${escapeHtml(String(capacite))}" class="border p-2 rounded w-full" required>
            <div class="flex gap-2">
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">Modifier</button>
                <button type="button" id="cancel-edit" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Annuler</button>
            </div>
        </form>
    `;

    // attach submit handler to edit form
    const editForm = document.getElementById('edit-form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) { editRessource(e, id); });
    }
    const cancelEdit = document.getElementById('cancel-edit');
    if (cancelEdit) cancelEdit.addEventListener('click', () => {
        document.getElementById('ressource-form').innerHTML = '';
    });
}

// --- EDIT (PUT) ---
async function editRessource(e, id) {
    e.preventDefault();
    const form = e.target;
    const payload = {
        nom: form.nom.value,
        description: form.description.value,
        localisation: form.localisation.value,
        type: form.type.value,
        capacite: form.capacite.value
    };

    try {
        const res = await fetch('/api/ressources/' + id, {
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
            showFlash(data?.message || `Erreur (${res.status})`, true);
            return;
        }

        showFlash('Ressource modifiée.');
        document.getElementById('ressource-form').innerHTML = '';
        await fetchRessources();
    } catch (err) {
        console.error('editRessource error:', err);
        showFlash('Erreur réseau lors de la modification', true);
    }
}

// --- Event delegation pour Edit/Delete (boutons dynamiques) ---
document.getElementById('ressources-list').addEventListener('click', function(e) {
    const del = e.target.closest('.delete-btn');
    if (del) {
        const id = del.getAttribute('data-id');
        deleteRessource(id);
        return;
    }
    const edit = e.target.closest('.edit-btn');
    if (edit) {
        const id = edit.getAttribute('data-id');
        loadRessourceAndShowForm(id);
        return;
    }
});

// initial load
fetchRessources();
</script>


@endsection
