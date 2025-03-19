<?php

namespace Database\Seeders;

use App\Models\Prescription;
use App\Models\PrescriptionMedication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء وصفة طبية
        $prescription = Prescription::create([
            'prescription_number' => 'RX-' . date('Y') . '-00001',
            'user_id' => 1, // تأكد من وجود مستخدم برقم 1
            'doctor_name' => 'د. أحمد محمد',
            'hospital_name' => 'مستشفى المدينة الطبي',
            'prescription_date' => now(),
            'image' => 'prescriptions/default.jpg',
            'status' => 'pending',
        ]);

        // إضافة الأدوية للوصفة
        PrescriptionMedication::create([
            'prescription_id' => $prescription->id,
            'medication_id' => 2, // أموكسيسيلين
            'quantity' => 1,
            'dosage_instructions' => 'كبسولة ثلاث مرات يومياً',
            'status' => 'pending',
        ]);
    }
}
