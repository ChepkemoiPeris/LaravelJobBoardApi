<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobType;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Full-time', 'Part-time', 'Contract', 'Internship', 'Freelance'];

        foreach ($types as $type) {
            JobType::create(['name' => $type]);
        }
    }
}
