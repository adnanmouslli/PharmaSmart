<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicationReminder extends Model
{
    protected $fillable = [
        'user_id',
        'medication_id',
        'medication_name',
        'strength',
        'dosage_form',
        'doses_per_day',
        'first_dose_time',
        'dose_interval',
        'instructions',
        'start_date',
        'end_date',
        'is_active',
        'notification_method'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function logs()
    {
        return $this->hasMany(MedicationReminderLog::class, 'reminder_id');
    }
}