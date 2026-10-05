<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientsExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Client::all()->map(function($client) {
            return [
                'Nom'              => $client->nom,
                'Téléphone'        => $client->telephone ?? '-',
                'Adresse'          => $client->adresse ?? '-',
                'Catégorie'        => ucfirst($client->categorie),
                'Remise'           => $client->remise . '%',
                'Date inscription' => $client->created_at->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Nom', 'Téléphone', 'Adresse', 'Catégorie', 'Remise', 'Date inscription'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}