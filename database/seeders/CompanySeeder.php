<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::insert([
            [
                'name' => 'TechNova Solutions',
                'description' => 'Innovative software development company.',
                'industry' => 'Information Technology',
                'address' => '123 Innovation Drive, Nairobi',
                'website' => 'https://technova.example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AgroGreen Ltd.',
                'description' => 'Sustainable agriculture and farming technologies.',
                'industry' => 'Agriculture',
                'address' => '45 Greenway Road, Eldoret',
                'website' => 'https://agrogreen.example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HealthFirst Kenya',
                'description' => 'Healthcare services and medical research.',
                'industry' => 'Healthcare',
                'address' => '78 Wellness Street, Mombasa',
                'website' => 'https://healthfirst.example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
