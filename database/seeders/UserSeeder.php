<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

         // Job Seeker
         User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
            'role' => 'job_seeker',
        ]);

        User::create([
            'name' => 'Job Seeker',
            'email' => 'jobseeker123@example.com',
            'password' => Hash::make('password'),
            'role' => 'job_seeker',
        ]);

        // Company User
        User::create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => Hash::make('password'),
            'role' => 'company_user',
            'company_id' => $company->id,
        ]);
    }
}
