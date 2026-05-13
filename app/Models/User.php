<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
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
}
