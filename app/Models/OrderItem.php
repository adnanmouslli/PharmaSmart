<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'medication_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    // علاقة مع الطلب
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // علاقة مع الدواء
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

}