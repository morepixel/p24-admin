<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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

    public function reports()
    {
        return $this->hasMany(Report::class, 'userId');
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
}
