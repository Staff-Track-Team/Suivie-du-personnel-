<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'heure_arrivee',
        'heure_depart',
        'pause_debut',
        'pause_fin',
        'heures_travaillees',
        'statut',
        'motif_absence',
        'justifie'
    ];

    protected $casts = [
        'date' => 'date',
        'heure_arrivee' => 'datetime:H:i',
        'heure_depart' => 'datetime:H:i',
        'pause_debut' => 'datetime:H:i',
        'pause_fin' => 'datetime:H:i',
        'heures_travaillees' => 'decimal:2',
        'justifie' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculerHeuresTravaillees()
    {
        if (!$this->heure_arrivee || !$this->heure_depart) {
            return 0;
        }

        $total = $this->heure_depart->diffInMinutes($this->heure_arrivee);

        // Soustraire la pause si elle existe
        if ($this->pause_debut && $this->pause_fin) {
            $pause = $this->pause_fin->diffInMinutes($this->pause_debut);
            $total -= $pause;
        }

        return $total / 60; // Convertir en heures
    }

    public function getRetardAttribute()
    {
        if (!$this->heure_arrivee) {
            return null;
        }

        $heureNormale = '08:00'; // Heure de début normale
        if ($this->heure_arrivee->format('H:i') > $heureNormale) {
            return $this->heure_arrivee->diffInMinutes($heureNormale);
        }

        return 0;
    }

    public function getEstEnRetardAttribute()
    {
        return $this->retard > 15; // Plus de 15 minutes de retard
    }
}
