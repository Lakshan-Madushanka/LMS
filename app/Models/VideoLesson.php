<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLesson extends Model
{
    use HasFactory;

    public function monthlySchedule()
    {
        return $this->hasOne(MonthlySchedule::class);
    }

    public function weeklySchedule()
    {
        $this->belongsTo(WeeklySchedule::class);
    }
}
