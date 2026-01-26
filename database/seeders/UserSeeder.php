<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Kyuusha',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Kyuuskie',
                'email' => 'user@gmail.com',
                'password' => bcrypt('password'),
                'no_hp' => '081234567890',
                'role' => 'user',
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}