<?php

namespace Database\Seeders;

use App\Models\Vacancy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vacancy::create([
            'company_id' => 1,
            'created_by' => 1,
            'title' => 'Senior Laravel Developer',
            'description' => 'We are looking for an experienced Laravel developer to join our team.',
            'location' => 'Tbilisi, Georgia',
            'employment_type' => 'full-time',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'expiration_date' => now()->addDays(25)
        ]);

        Vacancy::create([
            'company_id' => 2,
            'created_by' => 1,
            'title' => 'Marketing Specialist',
            'description' => 'Join our marketing team to create innovative campaigns.',
            'location' => 'Tbilisi, Georgia',
            'employment_type' => 'full-time',
            'status' => 'draft',
            'published_at' => null,
            'expiration_date' => null
        ]);

        Vacancy::create([
            'company_id' => 2,
            'created_by' => 1,
            'title' => 'Frontend Developer',
            'description' => 'Looking for a React specialist.',
            'location' => 'Remote',
            'employment_type' => 'remote',
            'status' => 'published',
            'published_at' => now()->subDays(2),
            'expiration_date' => now()->addDays(28)
        ]);
    }
}
