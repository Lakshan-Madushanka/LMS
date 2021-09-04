<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const names
        = [
            'super_admin' => 1,
            'admin'       => 2,
            'lecturer'    => 3,
            'student'     => 4,
        ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role'
            , 'role_id', 'user_id',)
            ->withTimestamps();
    }
}
