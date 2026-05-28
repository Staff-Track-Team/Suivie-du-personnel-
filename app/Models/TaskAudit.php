<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TaskAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 
        'changed_by', 
        'action', 
        'details',
        'old_status',
        'new_status'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getFormattedDetailsAttribute()
    {
        if (!$this->details) return '-';

        $data = json_decode($this->details, true);
        if (!$data) return $this->details;

        if ($this->action === 'created') {
            $msg = "Initialisation : ";
            $parts = [];
            if (isset($data['title'])) $parts[] = "Titre '{$data['title']}'";
            if (isset($data['assigned_to'])) {
                $user = User::find($data['assigned_to']);
                $parts[] = "Assigné à " . ($user ? $user->name : "l'ID {$data['assigned_to']}");
            }
            return count($parts) > 0 ? $msg . implode(', ', $parts) : "Création de la tâche";
        }

        if ($this->action === 'updated') {
            $changes = [];
            $labels = [
                'title' => 'Titre',
                'description' => 'Description',
                'start_date' => 'Date début',
                'end_date' => 'Date fin',
                'priority' => 'Priorité',
                'status' => 'Statut',
                'assigned_to' => 'Assignation'
            ];

            foreach ($data as $key => $value) {
                $label = $labels[$key] ?? $key;
                if ($key === 'assigned_to') {
                    $user = User::find($value);
                    $value = $user ? $user->name : "ID $value";
                }
                $changes[] = "$label → $value";
            }
            return "Modifications : " . implode(', ', $changes);
        }

        return $this->details;
    }
}
