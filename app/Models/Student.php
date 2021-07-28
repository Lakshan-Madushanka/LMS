<?php

namespace App\Models;

use App\Scopes\StudentGlobalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends User
{
    use HasFactory;

    public static function booted()
    {
        static::addGlobalScope(new StudentGlobalScope());
    }

    public function weeklyShedule()
    {
        return $this->belongsToMany(WeeklySchedule::class,
            'user_weekly_schedule', 'user_id','w_s_id')->withTimestamps();
    }
}
