<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculte;
use Illuminate\Support\Facades\DB;

class FaculteSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Faculte::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Créer les facultés selon VOTRE structure
        $facultes = [
            [
                'nom_faculte' => 'FSI',
                'description' => 'Faculté des Sciences de l\'Ingénieur'
            ],
            [
                'nom_faculte' => 'FSG',
                'description' => 'Faculté des Sciences de Gestion'
            ],
            [
                'nom_faculte' => 'TECHNOLOGIES AVANCÉES',
                'description' => 'Faculté des Technologies Avancées'
            ],
        ];
        
        foreach ($facultes as $faculte) {
            Faculte::create($faculte);
        }
        
        $this->command->info('✅ Facultés créées selon votre structure : FSI, FSG, TECHNOLOGIES AVANCÉES');
    }
}