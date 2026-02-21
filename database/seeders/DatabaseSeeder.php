<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin One',
            'email'    => 'admin1@wafa.id',
            'role'     => Role::Admin,
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name'     => 'Admin Two',
            'email'    => 'admin2@wafa.id',
            'role'     => Role::Admin,
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name'     => 'Staff One',
            'email'    => 'staff1@wafa.id',
            'role'     => Role::Staff,
            'password' => Hash::make('staff123'),
        ]);
    }
}
