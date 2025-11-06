<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\categories;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Technology',
            'Sports',
            'Lifestyle',
            'Education',
            'Health',
            'Business',
            'Ai',
            'Programming',
            'Random',
        ];

        foreach ($categories as $cat) {
            categories::updateOrCreate(['name' => $cat]);
        }

    }
}
