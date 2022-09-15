<?php

namespace App\Services;

class Slugify
{
    public static function slug(string $url): string
    {
        // Accents à enlever
        $accents = '/&([A-Za-z]{1,2})(grave|acute|circ|cedil|uml|lig);/';
        // Convertit tous les caractères éligibles
        $string_encoded = htmlentities($url, ENT_NOQUOTES, 'UTF-8');
        // Recherche les accents et remplace par la string encodé
        $strOne = preg_replace($accents, '$1', $string_encoded);
        // convertit les entités HTML spéciales en caractères
        $strTwo = htmlspecialchars_decode($strOne);
        // Retire les espaces et met tout en minuscule
        $strTree = strtolower(trim($strTwo));
        // remplace la regex par la chaine
        $strFour = preg_replace('/[^A-Za-z0-9-]+/', '-', $strTree);
        //  Test ..  //
        $str = preg_replace('/-+/', '-', $strFour);
        // ---------------//
        return rtrim($str, '-');
    }
}
