<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prescription extends Model
{
    protected $fillable = [
        'prescription_number',
        'user_id',
        'doctor_name',
        'hospital_name',
        'prescription_date',
        'image',
        'notes',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason'
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'reviewed_at' => 'datetime'
    ];

    // العلاقة مع المستخدم
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الصيدلي المراجع
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // العلاقة مع الأدوية
    public function medications(): BelongsToMany
    {
        return $this->belongsToMany(Medication::class, 'prescription_medications')
            ->withPivot(['quantity', 'dosage_instructions', 'status', 'notes'])
            ->withTimestamps();
    }


}