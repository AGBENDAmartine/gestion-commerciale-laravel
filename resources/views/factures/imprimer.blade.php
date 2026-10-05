<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $facture->numero }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            padding: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #f39c12;
        }
        .company-info {
            color: #666;
            margin-top: 5px;
            line-height: 1.6;
        }
        .facture-title {
            text-align: right;
        }
        .facture-title h2 {
            font-size: 28px;
            color: #2c3e50;
            letter-spacing: 3px;
        }
        .facture-title p {
            color: #666;
            margin-top: 5px;
            line-height: 1.8;
        }
        .facture-title .numero {
            font-size: 16px;
            font-weight: bold;
            color: #f39c12;
        }
        hr {
            border: none;
            border-top: 2px solid #f39c12;
            margin: 20px 0;
        }
        .client-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .client-info div {
            line-height: 1.8;
        }
        .client-info h6 {
            font-size: 11px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .client-info strong {
            font-size: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead tr {
            background: #2c3e50;
            color: #fff;
        }
        table thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
        }
        table thead th:last-child,
        table tbody td:last-child,
        table tfoot td:last-child {
            text-align: right;
        }
        table thead th:nth-child(4),
        table tbody td:nth-child(4) {
            text-align: center;
        }
        table tbody tr {
            border-bottom: 1px solid #eee;
        }
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tbody td {
            padding: 10px 12px;
        }
        table tfoot tr {
            background: #f39c12;
            color: #fff;
        }
        table tfoot td {
            padding: 12px;
            font-weight: bold;
            font-size: 15px;
        }
        .statut {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .statut-emise { background: #ffc107; color: #333; }
        .statut-payee { background: #28a745; color: #fff; }
        .statut-annulee { background: #dc3545; color: #fff; }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 11px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .signature div {
            text-align: center;
            width: 200px;
        }
        .signature .ligne {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { padding: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <!-- Bouton imprimer -->
    <div class="no-print" style="text-align:right; margin-bottom:20px;">
        <button onclick="window.print()"
            style="background:#f39c12; color:#fff; border:none; padding:10px 20px; border-radius:5px; cursor:pointer; font-size:14px;">
            🖨️ Imprimer
        </button>
        <button onclick="window.close()"
            style="background:#6c757d; color:#fff; border:none; padding:10px 20px; border-radius:5px; cursor:pointer; font-size:14px; margin-left:10px;">
            ✕ Fermer
        </button>
    </div>

    <!-- En-tête -->
    <div class="header">
        <div>
            <div class="company-name">🔧 YAONABA & FRERE</div>
            <div class="company-info">
                Vente de pneus et jantes<br>
                Lomé, Togo<br>
                Importation — Stockage — Vente
            </div>
        </div>
        <div class="facture-title">
            <h2>FACTURE</h2>
            <p>
                <span class="numero">{{ $facture->numero }}</span><br>
                Date : {{ \Carbon\Carbon::parse($facture->date_facture)->format('d/m/Y') }}<br>
                <span class="statut statut-{{ $facture->statut }}">
                    {{ ucfirst($facture->statut) }}
                </span>
            </p>
        </div>
    </div>

    <hr>

    <!-- Informations client et vendeur -->
    <div class="client-info">
        <div>
            <h6>Facturé à :</h6>
            <strong>{{ $facture->vente->client->nom }}</strong><br>
            @if($facture->vente->client->telephone)
                Tél : {{ $facture->vente->client->telephone }}<br>
            @endif
            @if($facture->vente->client->adresse)
                {{ $facture->vente->client->adresse }}
            @endif
        </div>
        <div style="text-align:right;">
            <h6>Vendeur :</h6>
            <strong>{{ $facture->vente->utilisateur->nom }}</strong><br>
            <small style="color:#999;">YAONABA ET FRERE</small>
        </div>
    </div>

    <!-- Tableau des produits -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Référence</th>
                <th>Désignation</th>
                <th>Qté</th>
                <th>Prix unitaire</th>
                <th>Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->vente->lignes as $i => $ligne)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $ligne->produit->reference }}</td>
                <td>
                    {{ $ligne->produit->marque }}
                    {{ $ligne->produit->dimension }}
                    <small style="color:#999;">({{ ucfirst($ligne->produit->type) }})</small>
                </td>
                <td style="text-align:center;">{{ $ligne->quantite }}</td>
                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F CFA</td>
                <td>{{ number_format($ligne->getSousTotal(), 0, ',', ' ') }} F CFA</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right;">TOTAL TTC :</td>
                <td>{{ number_format($facture->montant_total, 0, ',', ' ') }} F CFA</td>
            </tr>
        </tfoot>
    </table>

    <!-- Signatures -->
    <div class="signature">
        <div>
            <div class="ligne">Signature du client</div>
        </div>
        <div>
            <div class="ligne">Cachet & Signature YAONABA</div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>Merci de votre confiance — YAONABA ET FRERE</p>
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

</body>
</html>