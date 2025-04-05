<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'المدير',
            'last_name' => 'الرئيسي',
            'email' => 'admin@smartpharmacy.com',
            'phone' => '0501234567',
            'address' => 'الرياض، المملكة العربية السعودية',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);
    }
}