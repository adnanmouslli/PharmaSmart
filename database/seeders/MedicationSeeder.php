<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medications = [
            [
                'name' => 'باراسيتامول',
                'description' => 'مسكن للألم ومخفض للحرارة',
                'price' => 15.00,
                'category_id' => 1,
                'manufacturer' => 'شركة الدواء العربية',
                'dosage_form' => 'أقراص',
                'strength' => '500mg',
                'stock' => 100,
                'requires_prescription' => false,
                'is_active' => true,
            ],
            [
                'name' => 'أموكسيسيلين',
                'description' => 'مضاد حيوي واسع المجال',
                'price' => 45.00,
                'category_id' => 2,
                'manufacturer' => 'شركة الأدوية المتحدة',
                'dosage_form' => 'كبسولات',
                'strength' => '500mg',
                'stock' => 50,
                'requires_prescription' => true,
                'is_active' => true,
            ],
            [
                'name' => 'فيتامين د',
                'description' => 'مكمل غذائي لتعويض نقص فيتامين د',
                'price' => 30.00,
                'category_id' => 3,
                'manufacturer' => 'شركة الفيتامينات الطبية',
                'dosage_form' => 'أقراص',
                'strength' => '1000IU',
                'stock' => 75,
                'requires_prescription' => false,
                'is_active' => true,
            ],
        ];

        foreach ($medications as $medication) {
            Medication::create($medication);
        }
    }
}
