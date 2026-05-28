<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('is_admin', true)->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom Complet',
            'Email',
            'Téléphone',
            'Genre',
            'Date de Naissance',
            'Statut',
            'Date de Création',
        ];
    }

    public function map($admin): array
    {
        return [
            $admin->matricule,
            $admin->name,
            $admin->email,
            $admin->code_phone . ' ' . $admin->phone,
            $admin->gender ?? 'N/A',
            $admin->birthday ? \Carbon\Carbon::parse($admin->birthday)->format('d/m/Y') : 'N/A',
            $admin->status,
            $admin->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
