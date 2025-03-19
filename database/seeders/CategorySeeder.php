<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'مسكنات الألم',
                'icon' => 'fa-pills',
            ],
            [
                'name' => 'مضادات حيوية',
                'icon' => 'fa-capsules',
            ],
            [
                'name' => 'فيتامينات ومكملات',
                'icon' => 'fa-tablets',
            ],
            [
                'name' => 'أدوية القلب',
                'icon' => 'fa-heart',
            ],
            [
                'name' => 'أدوية السكري',
                'icon' => 'fa-syringe',
            ],
            [
                'name' => 'أدوية الضغط',
                'icon' => 'fa-heartbeat',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
