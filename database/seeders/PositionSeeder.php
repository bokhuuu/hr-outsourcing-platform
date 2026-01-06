<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::create([
            'company_id' => 1,
            'title' => 'Senior Developer',
            'description' => 'Lead development projects'
        ]);

        Position::create([
            'company_id' => 1,
            'title' => 'Developer',
            'description' => 'Build software solutions'
        ]);

        Position::create([
            'company_id' => 1,
            'title' => 'Team Lead',
            'description' => 'Manage development team'
        ]);

        Position::create([
            'company_id' => 2,
            'title' => 'Marketing Manager',
            'description' => 'Oversee marketing campaigns'
        ]);

        Position::create([
            'company_id' => 2,
            'title' => 'HR Manager',
            'description' => 'Handle recruitment'
        ]);

        Position::create([
            'company_id' => 2,
            'title' => 'Developer',
            'description' => 'Full-stack development'
        ]);

        Position::create([
            'company_id' => 3,
            'title' => 'Senior Developer',
            'description' => 'Technical architecture'
        ]);

        Position::create([
            'company_id' => 3,
            'title' => 'Product Manager',
            'description' => 'Define product strategy'
        ]);
    }
}
