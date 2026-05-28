<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'type',
        'date_debut',
        'date_fin',
        'nombre_jours',
        'motif',
        'statut',
        'commentaire_refus',
        'date_decision'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_decision' => 'date',
        'nombre_jours' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approuver($approverId, $commentaire = null)
    {
        $this->update([
            'statut' => 'Approuvé',
            'approved_by' => $approverId,
            'date_decision' => now(),
            'commentaire_refus' => null
        ]);
    }

    public function refuser($approverId, $commentaire)
    {
        $this->update([
            'statut' => 'Refusé',
            'approved_by' => $approverId,
            'date_decision' => now(),
            'commentaire_refus' => $commentaire
        ]);
    }

    public function getDureeEnJoursAttribute()
    {
        return $this->date_debut->diffInDays($this->date_fin) + 1;
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'En attente');
    }

    public function scopeApprouves($query)
    {
        return $query->where('statut', 'Approuvé');
    }

    public function scopeRefuses($query)
    {
        return $query->where('statut', 'Refusé');
    }
}
