<?php

if (!function_exists('annee_academique_courante')) {
    /**
     * Retourne l'année académique en cours basée sur la date réelle
     * 
     * Règle : 
     * - Si on est entre janvier et août (mois 1-8) → année en cours = (année-1)-(année)
     * - Si on est entre septembre et décembre (mois 9-12) → année en cours = (année)-(année+1)
     * 
     * @return string
     */
    function annee_academique_courante(): string
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        
        if ($currentMonth <= 8) { // Janvier à Août
            $start = $currentYear - 1;
            $end = $currentYear;
        } else { // Septembre à Décembre
            $start = $currentYear;
            $end = $currentYear + 1;
        }
        
        return $start . '-' . $end;
    }
}

if (!function_exists('annee_academique_suivante')) {
    /**
     * Retourne l'année académique suivante
     * 
     * @return string
     */
    function annee_academique_suivante(): string
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        
        if ($currentMonth <= 8) {
            $start = $currentYear;
            $end = $currentYear + 1;
        } else {
            $start = $currentYear + 1;
            $end = $currentYear + 2;
        }
        
        return $start . '-' . $end;
    }
}

if (!function_exists('annee_academique_precedente')) {
    /**
     * Retourne l'année académique précédente
     * 
     * @return string
     */
    function annee_academique_precedente(): string
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        
        if ($currentMonth <= 8) {
            $start = $currentYear - 2;
            $end = $currentYear - 1;
        } else {
            $start = $currentYear - 1;
            $end = $currentYear;
        }
        
        return $start . '-' . $end;
    }
}

if (!function_exists('annee_academique_liste')) {
    /**
     * Retourne une liste d'années académiques (courante + 2 précédentes + 2 suivantes)
     * 
     * @param int $nbPrecedentes Nombre d'années précédentes
     * @param int $nbSuivantes Nombre d'années suivantes
     * @return array
     */
    function annee_academique_liste(int $nbPrecedentes = 2, int $nbSuivantes = 2): array
    {
        $liste = [];
        $courante = annee_academique_courante();
        $liste[] = $courante;
        
        // Extraire l'année de début
        $parts = explode('-', $courante);
        $startYear = (int) $parts[0];
        
        // Années précédentes
        for ($i = 1; $i <= $nbPrecedentes; $i++) {
            $prevStart = $startYear - $i;
            $prevEnd = $prevStart + 1;
            $liste[] = $prevStart . '-' . $prevEnd;
        }
        
        // Années suivantes
        for ($i = 1; $i <= $nbSuivantes; $i++) {
            $nextStart = $startYear + $i;
            $nextEnd = $nextStart + 1;
            $liste[] = $nextStart . '-' . $nextEnd;
        }
        
        sort($liste);
        return $liste;
    }
}