<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Business;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskable = $this->taskable();
        return [
            'name' => fake()->sentence(3),
            'taskable_id' => $taskable::factory()->create(),
            'taskable_type' => $taskable,
            'status' => fake()->randomElement(TaskStatus::class),
        ];
    }

    /**
     * Get applicable taskable_type model name
     *
     * @return string
     */
    private function taskable(): string
    {
        return $this->faker->randomElement([Business::class, Person::class]);
    }
}
