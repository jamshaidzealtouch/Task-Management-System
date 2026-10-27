<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        // Generate project start date.
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');

        // Generate end date after the start date.
        $endDate = $this->faker->dateTimeBetween(
            $startDate,
            '+2 months'
        );

        return [
            // Project belongs to an organization.
            'organization_id' => Organization::factory(),

            'title' => $this->faker->sentence(3),

            'description' => $this->faker->paragraph(),

            'start_date' => $startDate->format('Y-m-d'),

            'end_date' => $endDate->format('Y-m-d'),

            'status' => fake()->randomElement(
                array_column(
                    ProjectStatus::cases(),
                    'value'
                )
            ),
        ];
    }
}