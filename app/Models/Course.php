<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    const status
        = [
            'upcomming', 'ongoing', 'withheld'
        ];

    public function users()
    {
        return $this->belongsToMany(Role::class, 'course_id', 'user_id')
            ->withPivot('course_in_charge');
    }

    public function payment()
    {
        return $this->hasOne(Course::class);
    }
}
