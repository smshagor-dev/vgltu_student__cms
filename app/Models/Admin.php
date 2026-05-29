<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $guard = 'admin';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'two_factor_confirmed_at' => 'datetime',
    ];

    public function hasTwoFactorEnabled(): bool
    {
        return ! empty($this->two_factor_secret) && $this->two_factor_confirmed_at !== null;
    }
}
