<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\JobType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobPostingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::inRandomOrder()->first()->id,
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(4),
            'location' => $this->faker->city() . ', Kenya',
            'min_salary' => $this->faker->numberBetween(40000, 60000),
            'max_salary' => $this->faker->numberBetween(80000, 120000),
            'job_type_id' => JobType::inRandomOrder()->first()->id,
            'status' => 'active',
            'deadline' => now()->addDays(rand(15, 60)),
        ];
    }
}
