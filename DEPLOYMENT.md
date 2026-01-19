# PhotoMatch - Guide de Déploiement en Production

Ce guide détaille les étapes pour déployer PhotoMatch en production.

## Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Option 1: Render (Recommandé)](#option-1-render-recommandé)
3. [Option 2: Railway (Alternative)](#option-2-railway-alternative)
4. [Configuration commune](#configuration-commune)
5. [Post-déploiement](#post-déploiement)
6. [Troubleshooting](#troubleshooting)

---

## Vue d'ensemble

### Stack technique

- **Backend:** Laravel 11 (PHP 8.2)
- **Base de données:** MySQL 8.0
- **Frontend:** Vite + Tailwind CSS + Alpine.js
- **Serveur web:** Nginx + PHP-FPM

### Composants nécessaires en production

| Composant          | Description                                          |
|--------------------|------------------------------------------------------|
| Web Service        | Application Laravel                                  |
| Base de données    | MySQL ou PostgreSQL                                  |
| Storage            | Pour les fichiers uploadés (S3, Cloudflare R2, etc.) |
| Worker (optionnel) | Pour les queues Laravel                              |

---

## Railway (Alternative)

Railway est plus simple que Render pour Laravel et offre MySQL natif.

### Étape 1: Configurer Railway

1. Va sur [railway.app](https://railway.app)
2. Connecte-toi avec GitHub
3. New Project → Deploy from GitHub repo

### Étape 2: Ajouter MySQL

1. Dans ton projet Railway → Add Service → Database → MySQL
2. Railway configure automatiquement les variables d'environnement

### Étape 3: Configurer le service Laravel

Crée un fichier `railway.toml` à la racine:

```toml
[build]
builder = "dockerfile"
dockerfilePath = "Dockerfile.prod"

[deploy]
healthcheckPath = "/up"
healthcheckTimeout = 300
restartPolicyType = "on_failure"
restartPolicyMaxRetries = 3
```

### Étape 4: Variables d'environnement

Railway détecte automatiquement les variables MySQL. Ajoute manuellement:

```
APP_NAME=PhotoMatch
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=${{RAILWAY_PUBLIC_DOMAIN}}

LOG_CHANNEL=stderr

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=<your-smtp-host>
MAIL_PORT=587
MAIL_USERNAME=<username>
MAIL_PASSWORD=<password>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@photomatch.com
MAIL_FROM_NAME=PhotoMatch
```

Railway injecte automatiquement:

- `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`

Modifie `config/database.php` pour utiliser ces variables:

```php
'mysql' => [
    'host' => env('MYSQL_HOST', env('DB_HOST', '127.0.0.1')),
    'port' => env('MYSQL_PORT', env('DB_PORT', '3306')),
    'database' => env('MYSQL_DATABASE', env('DB_DATABASE', 'laravel')),
    'username' => env('MYSQL_USER', env('DB_USERNAME', 'root')),
    'password' => env('MYSQL_PASSWORD', env('DB_PASSWORD', '')),
    // ...
],
```

---

## Configuration commune

### Storage des fichiers (S3/Cloudflare R2)

Pour stocker les fichiers uploadés en production, utilise un service de stockage cloud.

#### Cloudflare R2 (recommandé, gratuit jusqu'à 10GB)

1. Crée un compte Cloudflare
2. Dashboard → R2 → Create bucket
3. Crée une API token avec permissions R2

Ajoute au `.env` de production:

```
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=<r2-access-key>
AWS_SECRET_ACCESS_KEY=<r2-secret-key>
AWS_DEFAULT_REGION=auto
AWS_BUCKET=photomatch
AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=true
```

Installe le package S3:

```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

### Service d'emails

Options gratuites/pas chères:

- **Mailgun:** 1000 emails/mois gratuit
- **Resend:** 3000 emails/mois gratuit
- **Postmark:** 100 emails/mois gratuit

---

## Post-déploiement

### Checklist

- [ ] Vérifier que l'application démarre (`/up` endpoint)
- [ ] Tester la connexion à la base de données
- [ ] Tester l'inscription/connexion
- [ ] Vérifier les logs pour les erreurs
- [ ] Configurer un domaine personnalisé (optionnel)
- [ ] Activer HTTPS (automatique sur Render/Railway)
- [ ] Configurer les backups de la base de données

### Commandes utiles post-déploiement

Via la console Render/Railway ou SSH:

```bash
# Générer la clé d'application (si pas déjà fait)
php artisan key:generate

# Vérifier les migrations
php artisan migrate:status

# Créer un utilisateur admin
php artisan tinker
> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin']);

# Vider les caches
php artisan optimize:clear
```

### Monitoring

- **Render:** Dashboard intégré avec logs et métriques
- **Railway:** Dashboard avec logs en temps réel
- **Sentry** (optionnel): Pour le tracking d'erreurs

---

## Troubleshooting

### Erreur 500 au démarrage

1. Vérifier que `APP_KEY` est défini
2. Vérifier les permissions du dossier `storage/`
3. Consulter les logs: `php artisan log:tail` ou dashboard

### Problèmes de base de données

1. Vérifier les credentials
2. Tester la connexion: `php artisan tinker` puis `DB::connection()->getPdo();`
3. S'assurer que les migrations ont tourné

### Assets CSS/JS non chargés

1. Vérifier que `npm run build` a réussi pendant le build
2. Vérifier que `APP_URL` est correct
3. Inspecter le HTML pour voir si les chemins sont corrects

### Erreurs de permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Comparatif des options

| Critère        | Render               | Railway        |
|----------------|----------------------|----------------|
| MySQL natif    | Non (PostgreSQL)     | Oui            |
| Prix minimum   | ~$7/mois             | ~$5/mois       |
| Simplicité     | Moyenne              | Haute          |
| Docker support | Oui                  | Oui            |
| Free tier      | Limité (90j pour DB) | $5 crédit/mois |
| Région EU      | Oui (Frankfurt)      | Oui            |

**Recommandation:** Railway si tu veux garder MySQL et une config simple. Render si tu préfères PostgreSQL ou si tu as besoin de plus de contrôle.
