@extends('layouts.app')

@section('titre', 'Enregistrer un achat')

@section('contenu')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-shopping-cart text-warning"></i> Enregistrer un achat</h5>
    <a href="{{ route('achats.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<form action="{{ route('achats.store') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Informations générales -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle text-warning"></i> Informations
                </div>
                <div class="card-body">

                    <!-- Type d'achat -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag"></i> Type d'achat *
                        </label>
                        <select name="type_achat" id="type_achat"
                            class="form-select @error('type_achat') is-invalid @enderror"
                            required onchange="toggleFournisseur()">
                            <option value="">-- Choisir --</option>
                            <option value="commande_fournisseur" {{ old('type_achat') == 'commande_fournisseur' ? 'selected' : '' }}>
                                Commande fournisseur
                            </option>
                            <option value="achat_port" {{ old('type_achat') == 'achat_port' ? 'selected' : '' }}>
                                Achat direct au port
                            </option>
                        </select>
                        @error('type_achat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fournisseur -->
                    <div class="mb-3" id="div_fournisseur">
                        <label class="form-label fw-bold">
                            <i class="fas fa-truck"></i> Fournisseur
                        </label>
                        <select name="id_fournisseur"
                            class="form-select @error('id_fournisseur') is-invalid @enderror">
                            <option value="">-- Choisir un fournisseur --</option>
                            @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id_fournisseur }}"
                                {{ old('id_fournisseur') == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                {{ $fournisseur->nom }} ({{ $fournisseur->pays }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_fournisseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Montant total (calculé auto) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill"></i> Montant total
                        </label>
                        <div class="input-group">
                            <input type="text" id="montant_total_display"
                                class="form-control bg-light fw-bold text-success"
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
                <div class="card-header d-flex justify-content-between">
                    <span><i class="fas fa-box text-warning"></i> Produits achetés</span>
                    <button type="button" class="btn btn-sm btn-primary" onclick="ajouterLigne()">
                        <i class="fas fa-plus"></i> Ajouter un produit
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0" id="table_produits">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire (F)</th>
                                <th>Sous-total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="lignes_achat">
                            <!-- Ligne ajoutée dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('achats.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer l'achat
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    // Liste des produits
    const produits = @json($produits);
    let ligneIndex = 0;

    // Afficher/masquer le fournisseur selon le type
    function toggleFournisseur() {
        const type = document.getElementById('type_achat').value;
        const div = document.getElementById('div_fournisseur');
        div.style.display = type === 'commande_fournisseur' ? 'block' : 'none';
    }

    // Ajouter une ligne de produit
    function ajouterLigne() {
        const tbody = document.getElementById('lignes_achat');
        const tr = document.createElement('tr');
        tr.id = 'ligne_' + ligneIndex;

        let options = '<option value="">-- Choisir --</option>';
        produits.forEach(p => {
            options += `<option value="${p.id_produit}">${p.reference} - ${p.marque} ${p.dimension}</option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="produits[${ligneIndex}][id_produit]"
                    class="form-select form-select-sm"
                    required>
                    ${options}
                </select>
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
                <input type="number"
                    name="produits[${ligneIndex}][prix]"
                    class="form-control form-control-sm prix"
                    min="0" value="0"
                    onchange="calculerSousTotal(${ligneIndex})"
                    required>
            </td>
            <td>
                <span id="sous_total_${ligneIndex}" class="fw-bold text-success">0 F</span>
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
    }

    // Calculer le sous-total d'une ligne
    function calculerSousTotal(index) {
        const ligne = document.getElementById('ligne_' + index);
        const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
        const prix = parseFloat(ligne.querySelector('.prix').value) || 0;
        const sousTotal = quantite * prix;

        document.getElementById('sous_total_' + index).textContent =
            sousTotal.toLocaleString('fr-FR') + ' F';

        calculerTotal();
    }

    // Calculer le total général
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

    // Ajouter une première ligne au chargement
    document.addEventListener('DOMContentLoaded', function() {
        ajouterLigne();
        toggleFournisseur();
    });
</script>
@endpush