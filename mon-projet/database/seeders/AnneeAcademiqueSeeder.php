<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnneeAcademique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnneeAcademiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Désactiver toutes les années existantes
            DB::table('annees_academiques')->update(['active' => false]);
            
            // Années académiques à créer
            $annees = [
                [
                    'annee' => '2024-2025',
                    'date_debut' => '2024-09-01',
                    'date_fin' => '2025-08-31',
                    'active' => false
                ],
                [
                    'annee' => '2025-2026',
                    'date_debut' => '2025-09-01',
                    'date_fin' => '2026-08-31',
                    'active' => true
                ],
                [
                    'annee' => '2026-2027',
                    'date_debut' => '2026-09-01',
                    'date_fin' => '2027-08-31',
                    'active' => false
                ],
            ];
            
            foreach ($annees as $anneeData) {
                AnneeAcademique::updateOrCreate(
                    ['annee' => $anneeData['annee']],
                    $anneeData
                );
            }
            
            $this->command->info('✅ Années académiques créées avec succès!');
            $this->command->info('📅 Année active: 2025-2026');
            
        } catch (\Exception $e) {
            Log::error('Erreur AnneeAcademiqueSeeder: ' . $e->getMessage());
            $this->command->error('❌ Erreur: ' . $e->getMessage());
        }
    }
}