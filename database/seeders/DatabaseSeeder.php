<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LaratrustSeeder::class);
        $this->call(ProductCategorySeeder::class);

        if (app()->environment('testing') || app()->isLocal()) {
            $this->call(ProductSeeder::class);
        }
    }
}
