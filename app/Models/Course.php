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
        return $this->belongsToMany(User::class, 'user_course', 'user_id','course_id'
            )
            ->withPivot('course_in_charge')->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
