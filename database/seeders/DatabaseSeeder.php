<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin One',
            'email' => 'admin1@wafa.id',
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@wafa.id',
            'password' => Hash::make('admin123'),
        ]);
    }
}
