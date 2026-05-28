<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'type',
        'fichier',
        'date_emission',
        'date_expiration',
        'description',
        'confidentiel'
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_expiration' => 'date',
        'confidentiel' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEstExpireAttribute()
    {
        if (!$this->date_expiration) {
            return false;
        }
        
        return $this->date_expiration->isPast();
    }

    public function getJoursAvantExpirationAttribute()
    {
        if (!$this->date_expiration) {
            return null;
        }
        
        return now()->diffInDays($this->date_expiration, false);
    }

    public function getAlerteExpirationAttribute()
    {
        $jours = $this->jours_avant_expiration;
        
        if ($jours === null || $jours < 0) {
            return null;
        }
        
        if ($jours <= 30) {
            return 'Urgent';
        } elseif ($jours <= 60) {
            return 'Attention';
        }
        
        return null;
    }

    public function scopeExpiresSoon($query, $jours = 30)
    {
        return $query->where('date_expiration', '<=', now()->addDays($jours))
                    ->where('date_expiration', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('date_expiration', '<', now());
    }

    public function scopeConfidentiels($query)
    {
        return $query->where('confidentiel', true);
    }

    public function scopePublics($query)
    {
        return $query->where('confidentiel', false);
    }
}
