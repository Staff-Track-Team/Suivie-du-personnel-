<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'matricule',
        'name',
        'email',
        'profil',
        'birthday',
        'gender',
        'code_phone',
        'phone',
        'status',
        'role',
        'password',
        'numero_cni',
        'numero_cnps',
        'situation_matrimoniale',
        'nombre_enfants',
        'niveau_education',
        'compte_bancaire',
        'nom_urgence',
        'contact_urgence',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'nombre_enfants' => 'integer',
        ];
    }

    public function employeeInfo()
    {
        return $this->hasOne(EmployeeInfo::class);
    }

    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function taskAudits()
    {
        return $this->hasMany(TaskAudit::class, 'changed_by');
    }

    // Relations RH
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function currentLeaveBalance()
    {
        return $this->hasOne(LeaveBalance::class)
                   ->where('annee', date('Y'));
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function performanceEvaluations()
    {
        return $this->hasMany(PerformanceEvaluation::class);
    }

    public function evaluationsAsEvaluator()
    {
        return $this->hasMany(PerformanceEvaluation::class, 'evaluator_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function leavesApprouves()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }

    // Méthodes utilitaires pour les rôles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManagerRH()
    {
        return $this->role === 'manager_rh';
    }

    public function isEmploye()
    {
        return $this->role === 'employe';
    }

    public function canManageRH()
    {
        return $this->isAdmin() || $this->isManagerRH();
    }

    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrateur',
            'manager_rh' => 'Manager RH',
            'employe' => 'Employé',
            default => 'Non défini'
        };
    }

    public function getAgeAttribute()
    {
        return $this->birthday ? $this->birthday->age : null;
    }

    public function getAncienneteAttribute()
    {
        $employeeInfo = $this->employeeInfo;
        if (!$employeeInfo || !$employeeInfo->hired_at) {
            return null;
        }
        
        return $employeeInfo->hired_at->diffInDays(now());
    }

    public function getAncienneteAnneesAttribute()
    {
        $anciennete = $this->anciennete;
        if (!$anciennete) {
            return null;
        }
        
        return floor($anciennete / 365);
    }
}
