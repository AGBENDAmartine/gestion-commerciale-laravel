<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { color: #f39c12; text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #2c3e50; color: white; padding: 8px; text-align: left; }
        td { padding: 7px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .total { font-weight: bold; background: #f39c12; color: white; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>YAONABA & FRERE</h2>
        <p>Rapport des ventes — Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Client</th>
                <th>Vendeur</th>
                <th>Montant Total</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventes as $i => $vente)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $vente->date_vente }}</td>
                <td>{{ $vente->client->nom ?? '-' }}</td>
                <td>{{ $vente->utilisateur->nom ?? '-' }}</td>
                <td><strong>{{ number_format($vente->montant_total, 0, ',', ' ') }} F CFA</strong></td>
                <td>{{ ucfirst($vente->statut ?? 'validée') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" style="text-align:right;">TOTAL</td>
                <td>{{ number_format($ventes->sum('montant_total'), 0, ',', ' ') }} F CFA</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        YAONABA & FRERE — Lomé, Togo — Document 
    </div>
</body>
</html>