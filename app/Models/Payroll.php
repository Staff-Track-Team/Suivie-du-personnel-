<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'periode_debut',
        'periode_fin',
        'salaire_base',
        'heures_supplementaires',
        'taux_horaire_sup',
        'montant_heures_sup',
        'prime_anciennete',
        'prime_responsabilite',
        'prime_performance',
        'autres_primes',
        'salaire_brut',
        'cnps_employee',
        'cnps_employer',
        'impot_sur_salaire',
        'autres_retentions',
        'total_retentions',
        'salaire_net',
        'date_paiement',
        'statut',
        'bulletin_pdf'
    ];

    protected $casts = [
        'periode_debut' => 'date',
        'periode_fin' => 'date',
        'date_paiement' => 'date',
        'salaire_base' => 'decimal:2',
        'heures_supplementaires' => 'decimal:2',
        'taux_horaire_sup' => 'decimal:2',
        'montant_heures_sup' => 'decimal:2',
        'prime_anciennete' => 'decimal:2',
        'prime_responsabilite' => 'decimal:2',
        'prime_performance' => 'decimal:2',
        'autres_primes' => 'decimal:2',
        'salaire_brut' => 'decimal:2',
        'cnps_employee' => 'decimal:2',
        'cnps_employer' => 'decimal:2',
        'impot_sur_salaire' => 'decimal:2',
        'autres_retentions' => 'decimal:2',
        'total_retentions' => 'decimal:2',
        'salaire_net' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculerSalaireBrut()
    {
        return $this->salaire_base 
            + $this->montant_heures_sup
            + $this->prime_anciennete
            + $this->prime_responsabilite
            + $this->prime_performance
            + $this->autres_primes;
    }

    public function calculerCNPS()
    {
        $salaireBrut = $this->calculerSalaireBrut();
        $this->cnps_employee = $salaireBrut * 0.028; // 2.8%
        $this->cnps_employer = $salaireBrut * 0.042; // 4.2%
    }

    public function calculerImpotSalaire()
    {
        // Calcul simplifié de l'impôt sur le salaire (Cameroun)
        $salaireBrut = $this->calculerSalaireBrut();
        $salaireImposable = $salaireBrut - $this->cnps_employee;
        
        // Tranches d'imposition (simplifiées)
        if ($salaireImposable <= 50000) {
            $this->impot_sur_salaire = 0;
        } elseif ($salaireImposable <= 100000) {
            $this->impot_sur_salaire = ($salaireImposable - 50000) * 0.10;
        } elseif ($salaireImposable <= 200000) {
            $this->impot_sur_salaire = 5000 + ($salaireImposable - 100000) * 0.15;
        } else {
            $this->impot_sur_salaire = 20000 + ($salaireImposable - 200000) * 0.25;
        }
    }

    public function calculerTotalRetentions()
    {
        $this->total_retentions = $this->cnps_employee 
            + $this->impot_sur_salaire 
            + $this->autres_retentions;
    }

    public function calculerSalaireNet()
    {
        $this->salaire_brut = $this->calculerSalaireBrut();
        $this->calculerCNPS();
        $this->calculerImpotSalaire();
        $this->calculerTotalRetentions();
        
        $this->salaire_net = $this->salaire_brut - $this->total_retentions;
    }

    public function genererReference()
    {
        $annee = date('Y');
        $mois = date('m');
        $sequence = Payroll::whereYear('periode_debut', $annee)
                          ->whereMonth('periode_debut', $mois)
                          ->count() + 1;
        
        $this->reference = "PAIE-{$annee}{$mois}-" . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function getPeriodeAttribute()
    {
        return $this->periode_debut->format('d/m/Y') . ' au ' . $this->periode_fin->format('d/m/Y');
    }
}
