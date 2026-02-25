<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;
use App\Models\Faculte;
use Illuminate\Support\Facades\DB;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Departement::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Récupérer les facultés
        $fsi = Faculte::where('nom_faculte', 'FSI')->first();
        $fsg = Faculte::where('nom_faculte', 'FSG')->first();
        $tech = Faculte::where('nom_faculte', 'TECHNOLOGIES AVANCÉES')->first();
        
        // Départements pour FSI
        if ($fsi) {
            $departementsFSI = [
                ['nom_departement' => 'GME', 'faculte_id' => $fsi->id], // Génie Mécanique
                ['nom_departement' => 'GC', 'faculte_id' => $fsi->id],  // Génie Civil
                ['nom_departement' => 'GL', 'faculte_id' => $fsi->id],  // Génie Logiciel
                ['nom_departement' => 'RT', 'faculte_id' => $fsi->id],  // Réseaux de Télécommunication
            ];
            
            foreach ($departementsFSI as $dep) {
                Departement::create($dep);
            }
        }
        
        // Départements pour FSG
        if ($fsg) {
            $departementsFSG = [
                ['nom_departement' => 'ECOPO', 'faculte_id' => $fsg->id], // Économie Politique
                ['nom_departement' => 'ECOLI', 'faculte_id' => $fsg->id], // Économie Rurale
                ['nom_departement' => 'GESTION', 'faculte_id' => $fsg->id],
            ];
            
            foreach ($departementsFSG as $dep) {
                Departement::create($dep);
            }
        }
        
        // Départements pour TECHNOLOGIES AVANCÉES
        if ($tech) {
            $departementsTech = [
                ['nom_departement' => 'Nucléaire', 'faculte_id' => $tech->id],
                ['nom_departement' => 'Drone', 'faculte_id' => $tech->id],
                ['nom_departement' => 'Systèmes de Défense', 'faculte_id' => $tech->id],
            ];
            
            foreach ($departementsTech as $dep) {
                Departement::create($dep);
            }
        }
        
        $this->command->info('✅ Départements créés selon votre structure !');
        $this->command->info('FSI : GME, GC, GL, RT');
        $this->command->info('FSG : ECOPO, ECOLI, GESTION');
        $this->command->info('TECHNOLOGIES AVANCÉES : Nucléaire, Drone, Systèmes de Défense');
    }
}