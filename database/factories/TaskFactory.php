<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'user_id' => User::where('role', 'employee')->inRandomOrder()->first()?->id,
            'manager_id' => User::where('role', 'manager')->inRandomOrder()->first()?->id,
            'due_date' => now()->addDays(rand(1, 30)), // Random due date within the next 30 days
            'parent_id' => null, // Assuming no parent tasks for simplicity
            'status' => 'pending', // Default status
        ];
    }
}
