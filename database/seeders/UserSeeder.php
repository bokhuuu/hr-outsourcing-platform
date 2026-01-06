<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ana Beridze',
            'email' => 'hr@platform.ge',
            'password' => Hash::make('password'),
            'role' => 'hr'
        ]);

        User::create([
            'name' => 'Giorgi Maisuradze',
            'email' => 'admin@platform.ge',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Nino Gelashvili',
            'email' => 'nino@connect.ge',
            'password' => Hash::make('password'),
            'role' => 'company_admin'
        ]);

        User::create([
            'name' => 'Davit Kapanadze',
            'email' => 'davit@hub.ge',
            'password' => Hash::make('password'),
            'role' => 'company_admin'
        ]);

        User::create([
            'name' => 'Tamar Lomidze',
            'email' => 'tamar@connect.ge',
            'password' => Hash::make('password'),
            'role' => 'employee'
        ]);

        User::create([
            'name' => 'Luka Khvichia',
            'email' => 'luka@connect.ge',
            'password' => Hash::make('password'),
            'role' => 'employee'
        ]);

        User::create([
            'name' => 'Mariam Tsereteli',
            'email' => 'mariam@hub.ge',
            'password' => Hash::make('password'),
            'role' => 'employee'
        ]);

        User::create([
            'name' => 'Sandro Jokhadze',
            'email' => 'sandro@hub.ge',
            'password' => Hash::make('password'),
            'role' => 'employee'
        ]);
    }
}
