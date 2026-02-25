<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enseignant;
use App\Models\Departement;
use Illuminate\Support\Facades\DB;

class EnseignantSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Enseignant::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Récupérer les départements
        $gl = Departement::where('nom_departement', 'GL')->first();
        $rt = Departement::where('nom_departement', 'RT')->first();
        $gme = Departement::where('nom_departement', 'GME')->first();
        $gc = Departement::where('nom_departement', 'GC')->first();
        $gestion = Departement::where('nom_departement', 'GESTION')->first();
        $drone = Departement::where('nom_departement', 'Drone')->first();
        
        // Enseignants pour GL (Génie Logiciel)
        if ($gl) {
            $enseignants = [
                [
                    'matricule' => 'ENS001',
                    'nom' => 'BIGIRIMANA',
                    'prenom' => 'Espoir',
                    'grade' => 'Professeur',
                    'specialite' => 'Programmation Web',
                    'email' => 'espoir.bigirimana@iscam.bi',
                    'telephone' => '79900111',
                    'departement_id' => $gl->id,
                ],
                [
                    'matricule' => 'ENS002',
                    'nom' => 'DUSABIMANA',
                    'prenom' => 'Ananie',
                    'grade' => 'Maître de Conférences',
                    'specialite' => 'Base de Données',
                    'email' => 'ananie.dusabimana@iscam.bi',
                    'telephone' => '79900222',
                    'departement_id' => $gl->id,
                ],
            ];
            
            foreach ($enseignants as $ens) {
                Enseignant::create($ens);
            }
        }
        
        // Enseignant pour RT
        if ($rt) {
            Enseignant::create([
                'matricule' => 'ENS003',
                'nom' => 'MANIRAKIZA',
                'prenom' => 'Alice',
                'grade' => 'Chargé de Cours',
                'specialite' => 'Réseaux',
                'email' => 'alice.mani@iscam.bi',
                'telephone' => '79900333',
                'departement_id' => $rt->id,
            ]);
        }
        
        // Enseignant pour Drone
        if ($drone) {
            Enseignant::create([
                'matricule' => 'ENS004',
                'nom' => 'HABONIMANA',
                'prenom' => 'Jean',
                'grade' => 'Professeur',
                'specialite' => 'Robotique',
                'email' => 'jean.haboni@iscam.bi',
                'telephone' => '79900444',
                'departement_id' => $drone->id,
            ]);
        }
        
        $this->command->info('✅ Enseignants créés avec succès !');
    }
}