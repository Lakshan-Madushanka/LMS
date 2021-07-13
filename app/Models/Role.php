<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const roles
        = [
            'super_admin',
            'admin',
            'lecturer',
            'student'
        ];

    public function users()
    {
        return $this->belongsToMany(Role::class, 'role_id', 'user_id')
            ->withTimestamps();
    }
}