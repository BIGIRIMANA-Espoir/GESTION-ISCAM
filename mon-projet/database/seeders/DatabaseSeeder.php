<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            AnneeAcademiqueSeeder::class,
            FaculteSeeder::class,          // ✅ NOUVEAU : Crée les facultés (FSI, FSG, TECHNOLOGIES AVANCÉES)
            DepartementSeeder::class,       // ✅ NOUVEAU : Crée tous les départements
            EnseignantSeeder::class,        // ✅ NOUVEAU : Crée des enseignants d'exemple
        ]);
    }
}