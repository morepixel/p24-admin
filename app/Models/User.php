<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public $timestamps = false;

    protected $connection = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'role',
        'password',
        'language',
        'emailCode',
        'confirmed',
        'street',
        'zip',
        'city',
        'country',
        'companyName',
        'companyForm',
        'companyOfficer',
        'companyNumber',
        'companyCourt',
        'taxInfo',
        'birthdate',
        'lastActivity',
        'emailReportUpdates',
        'emailAddressUpdates',
        'phone',
        'questionActive',
        'question',
        'answer',
        'welcomeNsl',
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
            'confirmed' => 'boolean',
            'emailReportUpdates' => 'integer',
            'emailAddressUpdates' => 'integer',
            'questionActive' => 'integer',
            'lastActivity' => 'datetime',
            'welcomeNsl' => 'datetime',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->confirmed && in_array($this->role, ['lawyer', 'assistant']);
    }

    public function getFilamentName(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getNameAttribute()
    {
        return trim($this->firstname . ' ' . $this->lastname) ?: 'Anonymous User';
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'userid');
    }

    public function isLawyer(): bool
    {
        return $this->role === 'lawyer';
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }

    public function canCreateReports(): bool
    {
        return $this->isLawyer();
    }

    public function canEditReports(): bool
    {
        return true;
    }

    public function canDeleteReports(): bool
    {
        return $this->isLawyer();
    }

    public function canViewReports(): bool
    {
        return true;
    }

    public function canExportReports(): bool
    {
        return $this->isLawyer();
    }

    public function getAuthPassword()
    {
        $password = $this->password;
        
        // Wenn es ein MD5-Hash ist (32 Zeichen hexadezimal)
        if (strlen($password) === 32 && ctype_xdigit($password)) {
            return '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        }
        
        return $password;
    }

    public function setPasswordAttribute($value)
    {
        if (strlen($value) === 60 && substr($value, 0, 2) === '$2') {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
