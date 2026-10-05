<?php

namespace App\Exports;

use App\Models\Vente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VentesExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Vente::with(['client', 'utilisateur'])->get()->map(function($vente) {
            return [
                'Date'          => $vente->date_vente,
                'Client'        => $vente->client->nom ?? '-',
                'Vendeur'       => $vente->utilisateur->nom ?? '-',
                'Montant Total' => number_format($vente->montant_total, 0, ',', ' ') . ' F CFA',
                'Statut'        => ucfirst($vente->statut ?? 'validée'),
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'Client', 'Vendeur', 'Montant Total', 'Statut'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}