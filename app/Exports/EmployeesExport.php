<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('employeeInfo', 'contract')
                     ->where('role', '!=', 'admin')
                     ->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom Complet',
            'Email',
            'Téléphone',
            'Rôle',
            'Département',
            'Poste',
            'Date d\'embauche',
            'Type de contrat',
            'Salaire de base',
            'Statut',
            'Date de Création',
        ];
    }

    public function map($employee): array
    {
        return [
            $employee->matricule,
            $employee->name,
            $employee->email,
            $employee->phone ?? 'N/A',
            $employee->role_label,
            $employee->employeeInfo ? ($employee->employeeInfo->department ?? 'N/A') : 'N/A',
            $employee->employeeInfo ? ($employee->employeeInfo->position ?? 'N/A') : 'N/A',
            $employee->employeeInfo && $employee->employeeInfo->hired_at ? \Carbon\Carbon::parse($employee->employeeInfo->hired_at)->format('d/m/Y') : 'N/A',
            $employee->contract ? ($employee->contract->type ?? 'N/A') : 'N/A',
            $employee->contract && $employee->contract->salaire_base ? number_format($employee->contract->salaire_base, 0, ',', ' ') . ' FCFA' : 'N/A',
            $employee->status,
            $employee->created_at->format('d/m/Y H:i'),
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
