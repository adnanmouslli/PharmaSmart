<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use  Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'address',
        'avatar',
        'is_admin',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    

    // العلاقة مع الطلبات
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // // العلاقة مع التذكيرات
    // public function reminders()
    // {
    //     return $this->hasMany(Reminder::class);
    // }

    // // العلاقة مع الاستشارات
    // public function consultations()
    // {
    //     return $this->hasMany(Consultation::class);
    // }

    // العلاقة مع الوصفات الطبية
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    // الحصول على الاسم الكامل
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}