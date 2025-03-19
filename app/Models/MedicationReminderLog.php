<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationReminderLog extends Model
{
    protected $fillable = [
        'reminder_id',
        'scheduled_time',
        'taken_at',
        'is_taken',
        'is_skipped',
        'notes'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'taken_at' => 'datetime',
        'is_taken' => 'boolean',
        'is_skipped' => 'boolean'
    ];

    // العلاقة مع التذكير الرئيسي
    public function reminder()
    {
        return $this->belongsTo(MedicationReminder::class, 'reminder_id');
    }

    // تحديد ما إذا كان التذكير متأخراً
    public function isLate()
    {
        return $this->scheduled_time->diffInHours(now()) > 1 && !$this->is_taken;
    }

    // تحديد ما إذا كان التذكير في الوقت المحدد
    public function isTakenOnTime()
    {
        if (!$this->is_taken || !$this->taken_at) {
            return false;
        }

        return $this->scheduled_time->diffInHours($this->taken_at) <= 1;
    }
}