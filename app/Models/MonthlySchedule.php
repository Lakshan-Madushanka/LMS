<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySchedule extends Model
{
    use HasFactory;

    const status
        = [
            'upcomming', 'ongoing', 'withheld'
        ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function courseMaterials()
    {
        return $this->belongsToMany(CourseMaterial::class, 'weekly_schedule_id',
            'course_material_id');
    }
}
