<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'prescription_id',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    // علاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة مع الوصفة الطبية
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    // علاقة مع عناصر الطلب
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // الحصول على حالة الطلب بالعربية
    public function getStatusTextAttribute()
    {
        return [
            'pending' => 'قيد المراجعة',
            'processing' => 'قيد التجهيز',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ][$this->status] ?? $this->status;
    }

    // التحقق من إمكانية إلغاء الطلب
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}