<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'type',
        'date_debut',
        'date_fin',
        'salaire_base',
        'poste',
        'description',
        'statut',
        'fichier_contrat'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'salaire_base' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDureeAttribute()
    {
        if (!$this->date_fin) {
            return 'Indéterminée';
        }
        return $this->date_debut->diffInDays($this->date_fin) . ' jours';
    }

    public function getEstActifAttribute()
    {
        $aujourdHui = now();
        return $this->statut === 'Actif' && 
               $this->date_debut <= $aujourdHui && 
               (!$this->date_fin || $this->date_fin >= $aujourdHui);
    }

    public function getJoursRestantsAttribute()
    {
        if (!$this->date_fin) {
            return null;
        }
        return max(0, now()->diffInDays($this->date_fin));
    }
}
