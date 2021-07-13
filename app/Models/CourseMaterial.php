<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    use HasFactory;

    const type
        = [
            'tute',
            'lecture',
            'note',
            'other'
        ];

    public function monthlySchedules()
    {
        return $this->belongsToMany(MonthlySchedule::class,
            'course_material_id',
            'monthly_schedule_id');
    }

    public function weeklySchedules()
    {
        return $this->belongsToMany(WeeklySchedule::class, 'course_material_id',
            'weekly_schedule_id');
    }
}