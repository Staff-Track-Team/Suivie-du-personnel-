<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'position',
        'hired_at',
        'address',
        'city',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
