<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends User
{
    use HasFactory;

    public function weeklyShedule()
    {
        return $this->belongsToMany(WeeklySchedule::class,
            'user_weekly_schedule', 'user_id', 'id');
    }
}
