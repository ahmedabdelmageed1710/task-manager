<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 managers
        User::factory()->count(5)->state(['role' => 'manager'])->create();

        // Create 20 employees
        User::factory()->count(20)->state(['role' => 'employee'])->create();
    }
}
