<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'Connect',
            'email' => 'info@connect.ge',
            'phone' => '+995 555 123 456',
            'address' => '123 Rustaveli Ave, Tbilisi'
        ]);

        Company::create([
            'name' => 'Hub',
            'email' => 'info@hub.ge',
            'phone' => '+995 511 123 456',
            'address' => '123 Chavchavadze Ave, Tbilisi'
        ]);

        Company::create([
            'name' => 'Space',
            'email' => 'info@space.ge',
            'phone' => '+995 599 123 456',
            'address' => '123 Tsereteli Ave, Tbilisi'
        ]);
    }
}
