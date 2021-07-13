<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Payment::class);
    }

    public function monthlySchedule()
    {
        return $this->belongsTo(MonthlySchedule::class);
    }
}
