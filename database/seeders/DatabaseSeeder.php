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
        $seeders = [
            UserSeeder::class,
        ];

        if (app()->environment('local')) {
            $seeders = array_merge($seeders, [
                //
            ]);
        }

        $this->call($seeders);
    }
}
