<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = User::where('role', 'employee')->get();
        $managers  = User::where('role', 'manager')->get();

        if ($employees->isEmpty() || $managers->isEmpty()) {
            $this->command->warn('No employees or managers found. Seed users first.');
            return;
        }

        Task::factory()->count(30)->make()->each(function ($task) use ($employees, $managers) {
            $task->user_id = $employees->random()->id;
            $task->manager_id = $managers->random()->id;
            $task->save();
        });
    }
}
