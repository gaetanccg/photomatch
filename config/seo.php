<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Information
    |--------------------------------------------------------------------------
    */
    'site_name' => env('APP_NAME', 'Trouve Ton Photographe'),
    'site_url' => env('APP_URL', 'https://trouvetonphotographe.fr'),

    /*
    |--------------------------------------------------------------------------
    | Default Meta Tags
    |--------------------------------------------------------------------------
    */
    'default' => [
        'title' => 'Trouve Ton Photographe - Trouvez le photographe ideal pour vos projets',
        'description' => 'Trouvez facilement un photographe professionnel pres de chez vous. Mariage, portrait, evenementiel, corporate : comparez les profils, consultez les portfolios et reservez en ligne.',
        'keywords' => 'photographe, photographe professionnel, photographe mariage, photographe portrait, photographe evenementiel, photographe corporate, reservation photographe, France',
        'author' => 'Trouve Ton Photographe',
        'robots' => 'index, follow',
    ],

    /*
    |--------------------------------------------------------------------------
    | Open Graph (Facebook, LinkedIn)
    |--------------------------------------------------------------------------
    */
    'og' => [
        'type' => 'website',
        'locale' => 'fr_FR',
        'image' => '/images/og-default.jpg',
        'image_width' => 1200,
        'image_height' => 630,
    ],

    /*
    |--------------------------------------------------------------------------
    | Twitter Card
    |--------------------------------------------------------------------------
    */
    'twitter' => [
        'card' => 'summary_large_image',
        'site' => '@TrouveTonPhoto',
    ],

    /*
    |--------------------------------------------------------------------------
    | Page-specific SEO
    |--------------------------------------------------------------------------
    */
    'pages' => [
        'home' => [
            'title' => 'Trouve Ton Photographe | Trouvez le photographe parfait pour vos projets',
            'description' => 'La plateforme de mise en relation entre clients et photographes professionnels. Trouvez, comparez et reservez le photographe ideal pour votre mariage, portrait, evenement ou shooting corporate.',
        ],
        'search' => [
            'title' => 'Rechercher un photographe | Trouve Ton Photographe',
            'description' => 'Recherchez parmi des centaines de photographes professionnels. Filtrez par specialite, localisation, tarif et disponibilite pour trouver le photographe parfait.',
        ],
        'photographers' => [
            'title' => 'Nos photographes professionnels | Trouve Ton Photographe',
            'description' => 'Decouvrez notre selection de photographes professionnels verifies. Portfolio, avis clients, tarifs : toutes les informations pour faire le bon choix.',
        ],
        'register' => [
            'title' => 'Inscription | Trouve Ton Photographe',
            'description' => 'Inscrivez-vous gratuitement sur Trouve Ton Photographe. Clients : trouvez le photographe ideal. Photographes : developpez votre activite.',
        ],
        'login' => [
            'title' => 'Connexion | Trouve Ton Photographe',
            'description' => 'Connectez-vous a votre espace personnel Trouve Ton Photographe pour gerer vos projets et reservations.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Legal Pages
    |--------------------------------------------------------------------------
    */
    'legal' => [
        'company_name' => 'Trouve Ton Photographe',
        'company_type' => 'SAS', // A adapter selon votre structure
        'company_capital' => '1 000', // Capital social
        'siret' => 'XXX XXX XXX XXXXX', // A completer
        'rcs' => 'Paris', // Ville RCS
        'vat_number' => 'FR XX XXX XXX XXX', // Numero TVA
        'address' => '123 Rue Example, 75001 Paris, France', // A completer
        'email' => 'contact@trouvetonphotographe.fr',
        'phone' => '+33 1 XX XX XX XX', // A completer
        'director' => 'Nom du dirigeant', // A completer
        'host_name' => 'OVH SAS', // Hebergeur
        'host_address' => '2 rue Kellermann, 59100 Roubaix, France',
        'dpo_email' => 'dpo@trouvetonphotographe.fr', // Data Protection Officer
    ],

    /*
    |--------------------------------------------------------------------------
    | Structured Data (Schema.org)
    |--------------------------------------------------------------------------
    */
    'schema' => [
        'organization' => [
            '@type' => 'Organization',
            'name' => 'Trouve Ton Photographe',
            'url' => 'https://trouvetonphotographe.fr',
            'logo' => 'https://trouvetonphotographe.fr/images/logo.png',
            'sameAs' => [
                'https://www.facebook.com/trouvetonphotographe',
                'https://www.instagram.com/trouvetonphotographe',
                'https://twitter.com/TrouveTonPhoto',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => 'contact@trouvetonphotographe.fr',
                'availableLanguage' => 'French',
            ],
        ],
    ],
];
