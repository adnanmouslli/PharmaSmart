<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء طلب
        $order = Order::create([
            'order_number' => 'ORD-' . date('Y') . '-00001',
            'user_id' => 1, // تأكد من وجود مستخدم برقم 1
            'total_amount' => 60.00,
            'status' => 'pending',
        ]);

        // إضافة منتجات للطلب
        OrderItem::create([
            'order_id' => $order->id,
            'medication_id' => 1, // باراسيتامول
            'quantity' => 2,
            'unit_price' => 15.00,
            'total_price' => 30.00,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'medication_id' => 3, // فيتامين د
            'quantity' => 1,
            'unit_price' => 30.00,
            'total_price' => 30.00,
            'status' => 'pending',
        ]);
    }
}
