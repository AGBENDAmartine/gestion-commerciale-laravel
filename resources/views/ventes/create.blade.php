@extends('layouts.app')

@section('titre', 'Nouvelle vente')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-cash-register text-warning"></i> Effectuer une vente</h5>
    <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<form action="{{ route('ventes.store') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Informations vente -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle text-warning"></i> Informations
                </div>
                <div class="card-body">

                   <!-- Client -->
<div class="mb-3">
    <label class="form-label fw-bold">
        <i class="fas fa-user"></i> Client *
    </label>
    <select name="id_client"
        class="form-select @error('id_client') is-invalid @enderror"
        onchange="afficherRemise(this)"
        required>
        <option value="">-- Choisir un client --</option>
        @foreach($clients as $client)
        <option value="{{ $client->id_client }}"
            data-remise="{{ $client->remise }}"
            data-categorie="{{ $client->categorie }}"
            {{ old('id_client') == $client->id_client ? 'selected' : '' }}>
            {{ $client->nom }}
            {{ $client->telephone ? '('.$client->telephone.')' : '' }}
            @if($client->remise > 0) — {{ $client->remise }}% remise @endif
        </option>
        @endforeach
    </select>
    @error('id_client')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    <!-- Affichage remise client -->
    <div id="div_remise" class="mt-2" style="display:none;">
        <div class="alert alert-success py-2 mb-0">
            <i class="fas fa-star"></i>
            Client <span id="label_categorie"></span> —
            Remise de <strong><span id="label_remise"></span>%</strong>
            appliquée automatiquement !
        </div>
    </div>

    <div class="mt-2">
        <a href="{{ route('clients.create') }}" target="_blank"
           class="btn btn-sm btn-outline-primary">
            <i class="fas fa-user-plus"></i> Nouveau client
        </a>
    </div>
</div>

                    <!-- Date -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar"></i> Date
                        </label>
                        <input type="text" class="form-control bg-light"
                            value="{{ date('d/m/Y') }}" disabled>
                    </div>

                    <!-- Montant total -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill"></i> Montant total
                        </label>
                        <div class="input-group">
                            <input type="text" id="montant_total_display"
                                class="form-control bg-light fw-bold text-success fs-5"
                                value="0" disabled>
                            <span class="input-group-text">F CFA</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Produits -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-box text-warning"></i> Produits vendus</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="ajouterLigne()">
                        <i class="fas fa-plus"></i> Ajouter un produit
                    </button>
                </div>

                <!-- Recherche produit -->
                <div class="p-3 bg-light border-bottom">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="recherche_produit"
                            class="form-control"
                            placeholder="Rechercher un produit par référence ou dimension..."
                            onkeyup="rechercherProduit()">
                    </div>
                    <div id="resultats_recherche" class="mt-2"></div>
                </div>

                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Stock</th>
                                <th>Quantité</th>
                                <th>Prix (F)</th>
                                <th>Sous-total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="lignes_vente">
                            <!-- Lignes ajoutées dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check-circle"></i> Valider la vente
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    const produits = @json($produits);
    const clients  = @json($clients);
    let ligneIndex = 0;
    let remiseClient = 0;

    // Afficher la remise du client sélectionné
    function afficherRemise(select) {
        const option = select.options[select.selectedIndex];
        remiseClient = parseFloat(option.getAttribute('data-remise')) || 0;
        const categorie = option.getAttribute('data-categorie') || 'normal';

        const divRemise = document.getElementById('div_remise');
        const labelRemise = document.getElementById('label_remise');
        const labelCategorie = document.getElementById('label_categorie');

        if (remiseClient > 0) {
            divRemise.style.display = 'block';
            labelRemise.textContent = remiseClient;
            const icones = { vip: '⭐⭐⭐ VIP', fidele: '⭐⭐ Fidèle', normal: '⭐ Normal' };
            labelCategorie.textContent = icones[categorie] || 'Normal';
            // Recalculer tous les prix avec la remise
            appliquerRemiseSurTout();
        } else {
            divRemise.style.display = 'none';
            remiseClient = 0;
            appliquerRemiseSurTout();
        }
        calculerTotal();
    }

    // Appliquer la remise sur tous les produits
    function appliquerRemiseSurTout() {
        document.querySelectorAll('[id^="ligne_"]').forEach(ligne => {
            const index = ligne.id.replace('ligne_', '');
            const prixOriginal = parseFloat(ligne.querySelector('.prix_original')?.value) || 0;
            if (prixOriginal > 0) {
                const prixAvecRemise = prixOriginal - (prixOriginal * remiseClient / 100);
                ligne.querySelector('.prix').value = Math.round(prixAvecRemise);
                calculerSousTotal(index);
            }
        });
        calculerTotal();
    }

    // Recherche produit en temps réel
    function rechercherProduit() {
        const query = document.getElementById('recherche_produit').value.toLowerCase();
        const resultats = document.getElementById('resultats_recherche');

        if (query.length < 2) {
            resultats.innerHTML = '';
            return;
        }

        const filtres = produits.filter(p =>
            p.reference.toLowerCase().includes(query) ||
            p.dimension.toLowerCase().includes(query) ||
            p.marque.toLowerCase().includes(query)
        );

        if (filtres.length === 0) {
            resultats.innerHTML = '<div class="alert alert-warning py-2">Aucun produit trouvé</div>';
            return;
        }

        let html = '<div class="list-group">';
        filtres.forEach(p => {
            const stock = p.quantite_stock > 0
                ? `<span class="badge bg-success">${p.quantite_stock} en stock</span>`
                : `<span class="badge bg-danger">Rupture</span>`;

            const prixFinal = remiseClient > 0
                ? Math.round(p.prix_vente - (p.prix_vente * remiseClient / 100))
                : p.prix_vente;

            html += `
                <button type="button"
                    class="list-group-item list-group-item-action d-flex justify-content-between"
                    onclick="ajouterProduit(${p.id_produit}, '${p.reference}', '${p.marque}', '${p.dimension}', ${p.prix_vente}, ${p.quantite_stock})">
                    <span>
                        <strong>${p.reference}</strong> —
                        ${p.marque} ${p.dimension}
                    </span>
                    <span>
                        ${stock} —
                        <strong>${prixFinal.toLocaleString('fr-FR')} F</strong>
                        ${remiseClient > 0 ? `<small class="text-success">(−${remiseClient}%)</small>` : ''}
                    </span>
                </button>`;
        });
        html += '</div>';
        resultats.innerHTML = html;
    }

    // Ajouter un produit depuis la recherche
    function ajouterProduit(id, reference, marque, dimension, prix, stock) {
        ajouterLigne(id, reference, marque, dimension, prix, stock);
        document.getElementById('recherche_produit').value = '';
        document.getElementById('resultats_recherche').innerHTML = '';
    }

    // Ajouter une ligne
    function ajouterLigne(id = '', reference = '', marque = '', dimension = '', prix = 0, stock = 0) {
        const tbody = document.getElementById('lignes_vente');
        const tr = document.createElement('tr');
        tr.id = 'ligne_' + ligneIndex;

        // Appliquer la remise sur le prix
        const prixFinal = remiseClient > 0
            ? Math.round(prix - (prix * remiseClient / 100))
            : prix;

        let options = '<option value="">-- Choisir --</option>';
        produits.forEach(p => {
            const selected = p.id_produit == id ? 'selected' : '';
            options += `<option value="${p.id_produit}" ${selected}
                data-prix="${p.prix_vente}"
                data-stock="${p.quantite_stock}">
                ${p.reference} - ${p.marque} ${p.dimension}
            </option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="produits[${ligneIndex}][id_produit]"
                    class="form-select form-select-sm"
                    onchange="produitChange(this, ${ligneIndex})"
                    required>
                    ${options}
                </select>
            </td>
            <td>
                <span id="stock_${ligneIndex}"
                    class="badge bg-${stock > 0 ? 'success' : 'danger'}">
                    ${stock}
                </span>
            </td>
            <td>
                <input type="number"
                    name="produits[${ligneIndex}][quantite]"
                    class="form-control form-control-sm quantite"
                    min="1" value="1"
                    onchange="calculerSousTotal(${ligneIndex})"
                    required>
            </td>
            <td>
                <!-- Prix original caché -->
                <input type="hidden" class="prix_original" value="${prix}">
                <input type="number"
                    name="produits[${ligneIndex}][prix]"
                    class="form-control form-control-sm prix"
                    min="0" value="${prixFinal}"
                    onchange="calculerSousTotal(${ligneIndex})"
                    required>
                ${remiseClient > 0 && prix > 0 ? `<small class="text-success">−${remiseClient}% appliqué</small>` : ''}
            </td>
            <td>
                <span id="sous_total_${ligneIndex}" class="fw-bold text-success">
                    ${prixFinal.toLocaleString('fr-FR')} F
                </span>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger"
                    onclick="supprimerLigne(${ligneIndex})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        ligneIndex++;
        calculerTotal();
    }

    // Mettre à jour le prix quand on change de produit
    function produitChange(select, index) {
        const option = select.options[select.selectedIndex];
        const prixOriginal = parseFloat(option.getAttribute('data-prix')) || 0;
        const stock = option.getAttribute('data-stock') || 0;

        const prixFinal = remiseClient > 0
            ? Math.round(prixOriginal - (prixOriginal * remiseClient / 100))
            : prixOriginal;

        const ligne = document.getElementById('ligne_' + index);
        ligne.querySelector('.prix_original').value = prixOriginal;
        ligne.querySelector('.prix').value = prixFinal;

        document.getElementById('stock_' + index).textContent = stock;
        document.getElementById('stock_' + index).className =
            'badge bg-' + (stock > 0 ? 'success' : 'danger');

        calculerSousTotal(index);
    }

    // Calculer le sous-total
    function calculerSousTotal(index) {
        const ligne = document.getElementById('ligne_' + index);
        const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
        const prix = parseFloat(ligne.querySelector('.prix').value) || 0;
        const sousTotal = quantite * prix;

        document.getElementById('sous_total_' + index).textContent =
            sousTotal.toLocaleString('fr-FR') + ' F';

        calculerTotal();
    }

    // Calculer le total
    function calculerTotal() {
        let total = 0;
        document.querySelectorAll('[id^="sous_total_"]').forEach(el => {
            const val = el.textContent.replace(/[^0-9]/g, '');
            total += parseFloat(val) || 0;
        });
        document.getElementById('montant_total_display').value =
            total.toLocaleString('fr-FR');
    }

    // Supprimer une ligne
    function supprimerLigne(index) {
        const ligne = document.getElementById('ligne_' + index);
        if (ligne) {
            ligne.remove();
            calculerTotal();
        }
    }

    // Ajouter une ligne au chargement
    document.addEventListener('DOMContentLoaded', function() {
        ajouterLigne();
    });
</script>
@endpush