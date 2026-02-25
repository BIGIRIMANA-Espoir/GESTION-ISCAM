<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@iscam.bi')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@iscam.bi',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]);
            $this->command->info('Admin créé avec succès!');
        } else {
            $this->command->info('Admin existe déjà, aucune action nécessaire.');
        }
    }
}