<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\JobType;
use Illuminate\Support\Str;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::all()->each(function ($company) {
            JobPosting::factory()->count(3)->create(['company_id' => $company->id]);
        });
    }
}
