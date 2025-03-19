<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionMedication extends Model
{
    protected $fillable = [
        'prescription_id',
        'medication_id',
        'quantity',
        'dosage_instructions',
        'status',
        'notes',
    ];

    // تعريف العلاقة مع الوصفات الطبية
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    // تعريف العلاقة مع الأدوية
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
