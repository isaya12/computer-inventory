<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CetegoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptops',
                'description' => 'Best laptops for work and gaming',
                'is_active' => true
            ],
            [
                'name' => 'Smartphones',
                'description' => 'Latest smartphones with great features',
                'is_active' => true
            ],
            [
                'name' => 'Tablets',
                'description' => 'Portable devices for entertainment',
                'is_active' => true
            ],
            // Add more categories as needed
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
