<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function mentions(): View
    {
        return view('legal.mentions', [
            'metaTitle' => 'Mentions legales | Trouve Ton Photographe',
            'metaDescription' => 'Mentions legales du site Trouve Ton Photographe - Informations sur l\'editeur, l\'hebergeur et les droits de propriete intellectuelle.',
            'noindex' => true,
        ]);
    }

    public function cgu(): View
    {
        return view('legal.cgu', [
            'metaTitle' => 'Conditions Generales d\'Utilisation | Trouve Ton Photographe',
            'metaDescription' => 'Conditions Generales d\'Utilisation de la plateforme Trouve Ton Photographe. Regles d\'utilisation pour les clients et les photographes.',
            'noindex' => true,
        ]);
    }

    public function privacy(): View
    {
        return view('legal.privacy', [
            'metaTitle' => 'Politique de Confidentialite | Trouve Ton Photographe',
            'metaDescription' => 'Politique de confidentialite et protection des donnees personnelles sur Trouve Ton Photographe. RGPD et droits des utilisateurs.',
            'noindex' => true,
        ]);
    }

    public function cookies(): View
    {
        return view('legal.cookies', [
            'metaTitle' => 'Politique des Cookies | Trouve Ton Photographe',
            'metaDescription' => 'Information sur l\'utilisation des cookies sur Trouve Ton Photographe et comment gerer vos preferences.',
            'noindex' => true,
        ]);
    }
}
