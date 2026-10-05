<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        h2 { color: #f39c12; text-align: center; margin: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #f39c12; padding-bottom: 10px; }
        .bilan { display: flex; gap: 10px; margin-bottom: 20px; }
        .bilan-card { flex: 1; padding: 12px; border-radius: 6px; text-align: center; color: white; }
        .rouge { background: #e74c3c; }
        .vert  { background: #27ae60; }
        .orange{ background: #f39c12; }
        .bilan-card .number { font-size: 16px; font-weight: bold; }
        .bilan-card .label  { font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        th { background: #2c3e50; color: white; padding: 7px 8px; text-align: left; font-size: 10px; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) { background: #f9f9f9; }
        h4 { color: #2c3e50; border-left: 4px solid #f39c12; padding-left: 8px; margin-top: 20px; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #ddd; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>YAONABA & FRERE</h2>
        <p>Rapport des statistiques — {{ now()->year }} — Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Bilan financier -->
    <h4>Bilan financier {{ now()->year }}</h4>
    <table>
        <thead>
            <tr>
                <th>Indicateur</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total achats (année)</td>
                <td><strong>{{ number_format($bilan['total_achats'], 0, ',', ' ') }} F CFA</strong></td>
            </tr>
            <tr>
                <td>Total ventes (année)</td>
                <td><strong>{{ number_format($bilan['total_ventes'], 0, ',', ' ') }} F CFA</strong></td>
            </tr>
            <tr>
                <td>Bénéfice (année)</td>
                <td><strong>{{ number_format($bilan['benefice'], 0, ',', ' ') }} F CFA</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- CA par mois -->
    <h4>Chiffre d'affaires mensuel {{ now()->year }}</h4>
    @php
        $mois_noms = [
            1=>'Janvier', 2=>'Février', 3=>'Mars', 4=>'Avril',
            5=>'Mai', 6=>'Juin', 7=>'Juillet', 8=>'Août',
            9=>'Septembre', 10=>'Octobre', 11=>'Novembre', 12=>'Décembre'
        ];
    @endphp
    <table>
        <thead>
            <tr><th>Mois</th><th>Chiffre d'affaires</th></tr>
        </thead>
        <tbody>
            @forelse($ca_par_mois as $ca)
            <tr>
                <td>{{ $mois_noms[$ca->mois] }}</td>
                <td><strong>{{ number_format($ca->total, 0, ',', ' ') }} F CFA</strong></td>
            </tr>
            @empty
            <tr><td colspan="2">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Top produits -->
    <h4>Top 10 produits les plus vendus</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Référence</th>
                <th>Marque</th>
                <th>Dimension</th>
                <th>Qté vendue</th>
                <th>Recettes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($top_produits as $i => $produit)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $produit->reference }}</strong></td>
                <td>{{ $produit->marque }}</td>
                <td>{{ $produit->dimension }}</td>
                <td>{{ $produit->total_vendu }} unités</td>
                <td><strong>{{ number_format($produit->recettes, 0, ',', ' ') }} F CFA</strong></td>
            </tr>
            @empty
            <tr><td colspan="6">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        YAONABA & FRERE — Lomé, Togo — Document généré  le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>