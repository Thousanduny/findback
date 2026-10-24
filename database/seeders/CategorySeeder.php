<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Электроника',
            'Документы',
            'Одежда и аксессуары',
            'Ключи',
            'Кошельки и карты',
            'Другое'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
