<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evaluator_id',
        'date_evaluation',
        'periode',
        'competences_techniques',
        'qualite_travail',
        'productivite',
        'ponctualite',
        'discipline',
        'collaboration',
        'note_generale',
        'points_forts',
        'axes_amelioration',
        'commentaires',
        'objectifs_prochain_periode',
        'statut'
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'competences_techniques' => 'decimal:2',
        'qualite_travail' => 'decimal:2',
        'productivite' => 'decimal:2',
        'ponctualite' => 'decimal:2',
        'discipline' => 'decimal:2',
        'collaboration' => 'decimal:2',
        'note_generale' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function calculerNoteGenerale()
    {
        $total = $this->competences_techniques 
            + $this->qualite_travail 
            + $this->productivite 
            + $this->ponctualite 
            + $this->discipline 
            + $this->collaboration;
        
        $this->note_generale = round($total / 6, 2);
        $this->save();
    }

    public function getNiveauPerformanceAttribute()
    {
        if ($this->note_generale >= 4.5) return 'Excellent';
        if ($this->note_generale >= 3.5) return 'Très bon';
        if ($this->note_generale >= 2.5) return 'Bon';
        if ($this->note_generale >= 1.5) return 'Moyen';
        return 'Insuffisant';
    }

    public function getCouleurNoteAttribute()
    {
        if ($this->note_generale >= 4.5) return 'text-green-600';
        if ($this->note_generale >= 3.5) return 'text-blue-600';
        if ($this->note_generale >= 2.5) return 'text-yellow-600';
        if ($this->note_generale >= 1.5) return 'text-orange-600';
        return 'text-red-600';
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'Validé');
    }

    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'Brouillon');
    }

    public function scopeArchives($query)
    {
        return $query->where('statut', 'Archivé');
    }
}
