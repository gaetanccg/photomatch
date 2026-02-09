# PhotoMatch

Plateforme de mise en relation entre clients et photographes professionnels.

## Quick Start (Docker)

```bash
# Clone et lancement
git clone <repo-url> photomatch
cd photomatch
make install
```

L'application sera disponible sur http://localhost:8080

> Le premier lancement installe automatiquement Laravel 11, Breeze, les dépendances et exécute les migrations.

## Services

| Service     | URL                   | Description       |
|-------------|-----------------------|-------------------|
| Application | http://localhost:8080 | Laravel + Nginx   |
| phpMyAdmin  | http://localhost:8081 | Gestion BDD       |
| Mailpit     | http://localhost:8025 | Capture emails    |
| Vite HMR    | http://localhost:5173 | Hot reload assets |

## Comptes de test

| Rôle        | Email                       | Mot de passe |
|-------------|-----------------------------|--------------|
| Admin       | admin@photomatch.test       | password     |
| Client      | client@photomatch.test      | password     |
| Photographe | photographe@photomatch.test | password     |

## Commandes Make

```bash
make install     # Premier lancement (build + up)
make up          # Démarrer les containers
make down        # Arrêter les containers
make logs        # Voir tous les logs
make logs-php    # Logs PHP uniquement
make shell       # Shell dans le container PHP
make fresh       # Reset BDD (migrate:fresh --seed)
make test        # Lancer les tests
make clean       # Supprimer volumes et containers
```

### Commandes Artisan/Composer/NPM

```bash
make artisan migrate:status
make artisan make:model Photo -mfc
make composer require package/name
make npm install package-name
```

## Structure du projet

```
photomatch/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Notifications/
│   └── Policies/
├── database/
│   ├── migrations/
│   └── seeders/
├── docker/
│   ├── php/
│   │   ├── Dockerfile
│   │   ├── entrypoint.sh
│   │   └── php.ini
│   ├── nginx/
│   │   └── default.conf
│   └── mysql/
│       └── my.cnf
├── resources/
│   └── views/
├── routes/
│   └── web.php
├── docker-compose.yml
├── Makefile
└── .env.example
```

## Stack technique

- **Backend**: Laravel 11, PHP 8.2
- **Frontend**: Blade, Tailwind CSS 3, Alpine.js
- **Auth**: Laravel Breeze
- **Database**: MySQL 8.0
- **Dev Tools**: Vite, Mailpit, phpMyAdmin

## Fonctionnalités

### Client

- Création et gestion de projets photo
- Recherche avancée de photographes (lieu, spécialité, budget)
- Envoi et suivi de demandes
- Notifications email et in-app

### Photographe

- Profil professionnel avec portfolio
- Gestion des spécialités et tarifs
- Calendrier de disponibilités
- Réception et traitement des demandes

### Admin

- Dashboard avec statistiques
- Gestion des utilisateurs
- Modération du contenu

## Installation manuelle (sans Docker)

```bash
# Prérequis: PHP 8.2+, Composer, MySQL 8.0+, Node.js 18+

git clone <repo-url> photomatch
cd photomatch
composer install
npm install
cp .env.example .env
php artisan key:generate
# Configurer DB_* dans .env
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

## Configuration

Variables d'environnement importantes (`.env`):

```env
# Ports Docker
APP_PORT=8080
VITE_PORT=5173
PMA_PORT=8081
MAILPIT_PORT=8025

# Base de données
DB_HOST=mysql          # 'mysql' pour Docker, '127.0.0.1' sinon
DB_DATABASE=photomatch
DB_USERNAME=photomatch
DB_PASSWORD=secret

# Mail (Mailpit en local)
MAIL_HOST=mailpit      # 'mailpit' pour Docker
MAIL_PORT=1025
```

## Développement

### Workflow quotidien

```bash
make up              # Démarrer l'environnement
# Le service node lance automatiquement `npm run dev` pour le HMR
make logs-php        # Debug si besoin
make down            # Fin de journée
```

### Créer une migration

```bash
make artisan make:migration create_photos_table
make artisan migrate
```

### Ajouter un modèle avec factory, migration et controller

```bash
make artisan make:model Photo -mfc
```

## Tests

### Technologie

- **PHPUnit** avec **Laravel Testing Framework** pour les tests unitaires et feature
- **Laravel Dusk** pour les tests E2E (browser)
- Base **SQLite en mémoire** pour l'isolation et la rapidité

### Structure des tests

```
tests/
├── TestCase.php
├── DuskTestCase.php
├── Browser/                          # Tests E2E (navigateur réel)
│   ├── AuthenticationTest.php        # Login, logout, inscription
│   ├── ClientWorkflowTest.php        # Workflow client complet
│   └── PhotographerWorkflowTest.php  # Workflow photographe complet
├── Feature/                          # Tests HTTP (sans navigateur)
│   ├── Auth/                         # Authentification, inscription, reset password
│   ├── Http/Controllers/             # Controllers (Portfolio, Client, Search, etc.)
│   └── ProfileTest.php
└── Unit/                             # Tests unitaires isolés
    ├── Actions/Booking/              # Actions métier (Create, Respond, Cancel)
    ├── Enums/                        # Enums (ProjectType, BookingStatus)
    ├── Services/                     # Services (Statistics, PortfolioUpload)
    ├── Queries/                      # Query builders
    ├── Transformers/                 # Transformers
    └── Observers/                    # Observers Eloquent
```

### Lancer les tests

```bash
# Tous les tests PHPUnit (Unit + Feature)
make test
# ou
php artisan test

# Par suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Fichier spécifique
php artisan test tests/Unit/Actions/Booking/CreateBookingRequestActionTest.php

# Filtrer par nom
php artisan test --filter="test_client_can_create_booking_request"
```

### Outils et patterns utilisés

- **RefreshDatabase** : réinitialise la BDD entre chaque test
- **Factories** : génération de données (`User::factory()`, `PhotoProject::factory()`)
- **Event::fake()** / **Notification::fake()** : mock des events et notifications
- **actingAs()** : simulation d'utilisateur authentifié

### Tests E2E avec Dusk

Les tests Dusk utilisent un vrai navigateur Chrome pour simuler les interactions utilisateur.

**Prérequis :**
- Chrome ou Chromium installé localement

**Lancer les tests E2E :**

```bash
# Démarrer le serveur de test (dans un terminal)
php artisan serve --port=8000

# Lancer les tests Dusk (dans un autre terminal)
php artisan dusk

# Un fichier spécifique
php artisan dusk tests/Browser/AuthenticationTest.php

# Un test spécifique
php artisan dusk --filter="test_user_can_login_as_client"

# Mode visible (sans headless) pour debug
php artisan dusk --browse
```

**Tests E2E disponibles :**

| Fichier | Ce qui est testé |
|---------|------------------|
| `AuthenticationTest` | Login, logout, inscription client/photographe |
| `ClientWorkflowTest` | Dashboard, création projet, recherche, envoi demande |
| `PhotographerWorkflowTest` | Dashboard, profil, demandes, accepter/refuser |

**Screenshots d'erreurs :**
En cas d'échec, les screenshots sont sauvegardés dans `tests/Browser/screenshots/`.

## Troubleshooting

### Les containers ne démarrent pas

```bash
docker compose logs    # Voir les erreurs
make rebuild           # Reconstruire sans cache
```

### Problèmes de permissions

```bash
# Ajuster UID/GID dans .env pour correspondre à votre utilisateur
id -u    # Votre UID
id -g    # Votre GID
```

### Reset complet

```bash
make clean             # Supprime tout
make install           # Réinstalle
```

## Roadmap

### MVP (en cours)

- [x] Configuration Docker
- [ ] Authentification multi-rôles
- [ ] CRUD projets client
- [ ] Profil photographe
- [ ] Système de demandes
- [ ] Notifications

### Phase 2

- [ ] Reviews et ratings
- [ ] Portfolio avec galerie
- [ ] Calendrier interactif
- [ ] Messagerie privée
- [ ] Système de favoris

### Phase 3

- [ ] API REST
- [ ] Paiements Stripe
- [ ] Génération contrats PDF
- [ ] Multi-langue
- [x] Tests automatisés
