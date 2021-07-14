<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklySchedule extends Model
{
    use HasFactory;

    const status
        = [
            'upcomming', 'ongoing', 'withheld'
        ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_weekly_schedule',
            'w_s_id', 'u_id');
    }

    public function videoLesson()
    {
        return $this->hasMany(VideoLesson::class);
    }

    public function courseMaterials()
    {
        return $this->belongsToMany(CourseMaterial::class,
            'weekly_schedule_course_material', 'weekly_schedule_id',
            'course_material_id')->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }
}
