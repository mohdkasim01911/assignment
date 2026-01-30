<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@example.com', // unique check
            ],
            [
                'name'      => 'Super Admin',
                'password'  => Hash::make('admin@123'),
                'role'      => 1, // assuming 1 = admin
                'parent_id' => null,
            ]
        );
    }
}
