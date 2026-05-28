<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'annee',
        'conges_payes_total',
        'conges_payes_pris',
        'conges_payes_restants',
        'maladies_total',
        'maladies_pris',
        'maladies_restants',
        'exceptionnels_total',
        'exceptionnels_pris',
        'exceptionnels_restants'
    ];

    protected $casts = [
        'annee' => 'integer',
        'conges_payes_total' => 'integer',
        'conges_payes_pris' => 'integer',
        'conges_payes_restants' => 'integer',
        'maladies_total' => 'integer',
        'maladies_pris' => 'integer',
        'maladies_restants' => 'integer',
        'exceptionnels_total' => 'integer',
        'exceptionnels_pris' => 'integer',
        'exceptionnels_restants' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function decrementerSolde($type, $nombreJours)
    {
        switch ($type) {
            case 'Congé payé':
                $this->conges_payes_pris += $nombreJours;
                $this->conges_payes_restants = max(0, $this->conges_payes_restants - $nombreJours);
                break;
            case 'Congé maladie':
                $this->maladies_pris += $nombreJours;
                $this->maladies_restants = max(0, $this->maladies_restants - $nombreJours);
                break;
            case 'Congé exceptionnel':
                $this->exceptionnels_pris += $nombreJours;
                $this->exceptionnels_restants = max(0, $this->exceptionnels_restants - $nombreJours);
                break;
        }
        
        $this->save();
    }

    public function incrementerSolde($type, $nombreJours)
    {
        switch ($type) {
            case 'Congé payé':
                $this->conges_payes_pris = max(0, $this->conges_payes_pris - $nombreJours);
                $this->conges_payes_restants = min($this->conges_payes_total, $this->conges_payes_restants + $nombreJours);
                break;
            case 'Congé maladie':
                $this->maladies_pris = max(0, $this->maladies_pris - $nombreJours);
                $this->maladies_restants = min($this->maladies_total, $this->maladies_restants + $nombreJours);
                break;
            case 'Congé exceptionnel':
                $this->exceptionnels_pris = max(0, $this->exceptionnels_pris - $nombreJours);
                $this->exceptionnels_restants = min($this->exceptionnels_total, $this->exceptionnels_restants + $nombreJours);
                break;
        }
        
        $this->save();
    }

    public static function getSoldeForUser($userId, $annee = null)
    {
        $annee = $annee ?? date('Y');
        
        return self::firstOrCreate(
            ['user_id' => $userId, 'annee' => $annee],
            [
                'conges_payes_total' => 30,
                'conges_payes_pris' => 0,
                'conges_payes_restants' => 30,
                'maladies_total' => 15,
                'maladies_pris' => 0,
                'maladies_restants' => 15,
                'exceptionnels_total' => 5,
                'exceptionnels_pris' => 0,
                'exceptionnels_restants' => 5
            ]
        );
    }
}
