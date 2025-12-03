<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'status',
        'is_deleted',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the company that owns the user
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role == 0 && $this->company_id === null;
    }

    /**
     * Check if user is company admin
     */
    public function isCompanyAdmin()
    {
        return $this->role == 1 && $this->company_id !== null;
    }

    /**
     * Check if user is company manager
     */
    public function isCompanyManager()
    {
        return $this->role == 2 && $this->company_id !== null;
    }

    /**
     * Check if user is company user
     */
    public function isCompanyUser()
    {
        return $this->role == 3 && $this->company_id !== null;
    }

    /**
     * Get role name
     */
    public function getRoleName()
    {
        return match($this->role) {
            0 => 'Super Admin',
            1 => 'Company Admin',
            2 => 'Company Manager',
            3 => 'Company User',
            default => 'Unknown'
        };
    }
}
