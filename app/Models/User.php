<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable, LogsActivity;

    protected $fillable = ['name', 'email', 'password', 'nivel'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->nivel === 'admin';
    }

    public function isEditor(): bool
    {
        return in_array($this->nivel, ['admin', 'editor']);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['name', 'email', 'nivel']);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // No ambiente local, permitimos acesso. Em produção você pode filtrar por isAdmin()
    }
}
