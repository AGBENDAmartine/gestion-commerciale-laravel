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
        .badge-vip    { background: #f39c12; color: white; padding: 2px 8px; border-radius: 4px; }
        .badge-fidele { background: #007bff; color: white; padding: 2px 8px; border-radius: 4px; }
        .badge-normal { background: #6c757d; color: white; padding: 2px 8px; border-radius: 4px; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>YAONABA & FRERE</h2>
        <p>Liste des clients — Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Catégorie</th>
                <th>Remise</th>
                <th>Date inscription</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $i => $client)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $client->nom }}</strong></td>
                <td>{{ $client->telephone ?? '-' }}</td>
                <td>{{ $client->adresse ?? '-' }}</td>
                <td>
                    <span class="badge-{{ $client->categorie }}">
                        {{ ucfirst($client->categorie) }}
                    </span>
                </td>
                <td>{{ $client->remise }}%</td>
                <td>{{ $client->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        YAONABA & FRERE — Lomé, Togo — Document 
    </div>
</body>
</html>