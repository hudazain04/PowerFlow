<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'Powerflow',
            'last_name' => "Super Admin",
            'phone_number' => "0937676698",
            'email' => 'huda1812zain@gmail.com',
            'password' => '123123123',
            'email_verified_at' => now()
        ]);
        $user->assignRole('superAdmin');
    }
}
