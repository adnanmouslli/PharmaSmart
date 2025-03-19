<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'manufacturer',
        'dosage_form',
        'strength',
        'stock',
        'requires_prescription',
        'is_active'
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prescriptions()
    {
        return $this->belongsToMany(Prescription::class, 'prescription_medications')
            ->withPivot('quantity', 'dosage_instructions', 'status', 'notes')
            ->withTimestamps();
    }
}
