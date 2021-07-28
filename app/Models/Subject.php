<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subject',
            'subject_id','user_id'

        )
            ->withPivot('subject_in_charge')
            ->withTimestamps();
    }

    public function courses()
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    public function weeklySchedules()
    {
        return $this->belongsToMany(WeeklySchedule::class)->withTimestamps();
    }
}
