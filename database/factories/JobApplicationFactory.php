<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JobPosting;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => JobPosting::inRandomOrder()->first()->id,
            'user_id' => User::where('role', 'job_seeker')->inRandomOrder()->first()->id,
            'cover_letter' => $this->faker->paragraph(3),
            'cv_path' => 'cv_samples/sample_cv.pdf',  
            'status'=>'applied',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
