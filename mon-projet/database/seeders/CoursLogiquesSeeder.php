<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cours;
use App\Models\Departement;
use Illuminate\Support\Facades\DB;

class CoursLogiquesSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver les contraintes
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Récupérer les départements par leur nom
        $gl = Departement::where('nom_departement', 'GL')->first();
        $rt = Departement::where('nom_departement', 'RT')->first();
        $gme = Departement::where('nom_departement', 'GME')->first();
        $gc = Departement::where('nom_departement', 'GC')->first();
        $ecopo = Departement::where('nom_departement', 'ECOPO')->first();
        $ecoli = Departement::where('nom_departement', 'ECOLI')->first();
        $gestion = Departement::where('nom_departement', 'GESTION')->first();
        $nucleaire = Departement::where('nom_departement', 'Nucléaire')->first();
        $drone = Departement::where('nom_departement', 'Drone')->first();
        $defense = Departement::where('nom_departement', 'Systèmes de Défense')->first();

        // 1. Cours pour GL (Génie Logiciel) - MIS À JOUR AVEC VOS NOUVEAUX COURS
        if ($gl) {
            $coursGL = [
                ['code_cours' => 'GL101', 'nom_cours' => 'Programmation Web', 'credits' => 4],
                ['code_cours' => 'GL102', 'nom_cours' => 'Base de Données', 'credits' => 4],
                ['code_cours' => 'GL103', 'nom_cours' => 'Algorithmique', 'credits' => 5],
                ['code_cours' => 'GL104', 'nom_cours' => 'Génie Logiciel', 'credits' => 4],
                ['code_cours' => 'GL105', 'nom_cours' => 'Sécurité Informatique', 'credits' => 3],
                // ✅ NOUVEAUX COURS AJOUTÉS
                ['code_cours' => 'GL106', 'nom_cours' => 'Modèles de conception et de Développement du logiciel', 'credits' => 4],
                ['code_cours' => 'GL107', 'nom_cours' => 'Test logiciels', 'credits' => 3],
                ['code_cours' => 'GL108', 'nom_cours' => 'Technologies de la programmation', 'credits' => 4],
            ];
            
            foreach ($coursGL as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $gl->id,
                    ]
                );
            }
        }

        // 2. Cours pour RT (Réseaux)
        if ($rt) {
            $coursRT = [
                ['code_cours' => 'RT101', 'nom_cours' => 'Réseaux TCP/IP', 'credits' => 4],
                ['code_cours' => 'RT102', 'nom_cours' => 'Télécommunications', 'credits' => 4],
                ['code_cours' => 'RT103', 'nom_cours' => 'Sécurité Réseaux', 'credits' => 3],
            ];
            
            foreach ($coursRT as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $rt->id,
                    ]
                );
            }
        }

        // 3. Cours pour GME (Génie Mécanique)
        if ($gme) {
            $coursGME = [
                ['code_cours' => 'GME101', 'nom_cours' => 'Résistance des Matériaux', 'credits' => 5],
                ['code_cours' => 'GME102', 'nom_cours' => 'Thermodynamique', 'credits' => 4],
                ['code_cours' => 'GME103', 'nom_cours' => 'Mécanique des Fluides', 'credits' => 4],
            ];
            
            foreach ($coursGME as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $gme->id,
                    ]
                );
            }
        }

        // 4. Cours pour GC (Génie Civil)
        if ($gc) {
            $coursGC = [
                ['code_cours' => 'GC101', 'nom_cours' => 'Béton Armé', 'credits' => 5],
                ['code_cours' => 'GC102', 'nom_cours' => 'Topographie', 'credits' => 4],
                ['code_cours' => 'GC103', 'nom_cours' => 'Matériaux de Construction', 'credits' => 4],
            ];
            
            foreach ($coursGC as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $gc->id,
                    ]
                );
            }
        }

        // 5. Cours pour ECOPO (Économie Politique)
        if ($ecopo) {
            $coursECOPO = [
                ['code_cours' => 'ECP101', 'nom_cours' => 'Microéconomie', 'credits' => 4],
                ['code_cours' => 'ECP102', 'nom_cours' => 'Macroéconomie', 'credits' => 4],
            ];
            
            foreach ($coursECOPO as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $ecopo->id,
                    ]
                );
            }
        }

        // 6. Cours pour GESTION
        if ($gestion) {
            $coursGEST = [
                ['code_cours' => 'GES101', 'nom_cours' => 'Comptabilité Générale', 'credits' => 4],
                ['code_cours' => 'GES102', 'nom_cours' => 'Marketing', 'credits' => 3],
                ['code_cours' => 'GES103', 'nom_cours' => 'Management', 'credits' => 4],
                ['code_cours' => 'GES104', 'nom_cours' => 'Finance', 'credits' => 4],
            ];
            
            foreach ($coursGEST as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $gestion->id,
                    ]
                );
            }
        }

        // 7. Cours pour ECOLI (Économie Rurale)
        if ($ecoli) {
            $coursECOLI = [
                ['code_cours' => 'ECL101', 'nom_cours' => 'Économie Agricole', 'credits' => 4],
                ['code_cours' => 'ECL102', 'nom_cours' => 'Développement Rural', 'credits' => 4],
            ];
            
            foreach ($coursECOLI as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $ecoli->id,
                    ]
                );
            }
        }

        // 8. Cours pour Nucléaire
        if ($nucleaire) {
            $coursNUC = [
                ['code_cours' => 'NUC101', 'nom_cours' => 'Physique Nucléaire', 'credits' => 5],
                ['code_cours' => 'NUC102', 'nom_cours' => 'Sécurité Radiologique', 'credits' => 4],
            ];
            
            foreach ($coursNUC as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $nucleaire->id,
                    ]
                );
            }
        }

        // 9. Cours pour Drone
        if ($drone) {
            $coursDRONE = [
                ['code_cours' => 'DRN101', 'nom_cours' => 'Pilotage de Drones', 'credits' => 4],
                ['code_cours' => 'DRN102', 'nom_cours' => 'Réglementation Aérienne', 'credits' => 3],
                ['code_cours' => 'DRN103', 'nom_cours' => 'Maintenance des Drones', 'credits' => 4],
            ];
            
            foreach ($coursDRONE as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $drone->id,
                    ]
                );
            }
        }

        // 10. Cours pour Systèmes de Défense
        if ($defense) {
            $coursDEF = [
                ['code_cours' => 'DEF101', 'nom_cours' => 'Systèmes d\'Armes', 'credits' => 5],
                ['code_cours' => 'DEF102', 'nom_cours' => 'Stratégie Militaire', 'credits' => 4],
                ['code_cours' => 'DEF103', 'nom_cours' => 'Cybersécurité Défense', 'credits' => 4],
            ];
            
            foreach ($coursDEF as $c) {
                Cours::firstOrCreate(
                    ['code_cours' => $c['code_cours']],
                    [
                        'nom_cours' => $c['nom_cours'],
                        'credits' => $c['credits'],
                        'departement_id' => $defense->id,
                    ]
                );
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $total = Cours::count();
        $this->command->info("✅ $total cours créés/logiques selon votre structure !");
        $this->command->info("📚 GL a maintenant 8 cours (dont les 3 nouveaux)");
    }
}