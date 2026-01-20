# Deploiement sur Render - Trouve Ton Photographe

Guide pas-a-pas pour deployer l'application sur Render.

---

## Pre-requis

- [ ] Compte GitHub avec le code source pousse
- [ ] Compte Render (https://render.com) - connecte avec GitHub
- [ ] Compte Supabase (https://supabase.com) - pour la base PostgreSQL gratuite
- [ ] Acces au serveur S3/Minio (deja configure sur s3.oceanetorresphotographie.fr)

---

## Etape 1 : Creer la base de donnees (Supabase)

Supabase offre une base PostgreSQL gratuite (500MB, largement suffisant).

1. Va sur **https://supabase.com** et cree un compte
2. Clique sur **"New Project"**
3. Configure :
    - **Organization:** Cree-en une si besoin
    - **Name:** `photomatch`
    - **Database Password:** Genere un mot de passe fort et note-le !
    - **Region:** West EU (Paris) ou la plus proche
4. Clique **"Create new project"** et attends ~2 minutes
5. Une fois cree, va dans **Project Settings** (icone engrenage) → **Database**
6. Section **"Connection string"** → selectionne **"URI"**
7. Copie l'URI et remplace `[YOUR-PASSWORD]` par ton mot de passe

L'URI ressemble a :

```
postgresql://postgres.[PROJECT-REF]:[PASSWORD]@aws-0-eu-west-3.pooler.supabase.com:6543/postgres
```

**Important :** Utilise le port `6543` (pooler) pour de meilleures performances.

---

## Etape 2 : Creer le Web Service

1. Va sur **https://dashboard.render.com**
2. Clique sur **"New +"** → **"Web Service"**
3. Connecte ton repo GitHub **photomatch**
4. Configure :
    - **Name:** `photomatch`
    - **Region:** Frankfurt (EU) ou la plus proche
    - **Branch:** `main`
    - **Runtime:** `Docker`
    - **Instance Type:** `Free` (ou Starter $7/mois pour plus de resources)

5. Clique **"Create Web Service"** (le build va echouer, c'est normal - on doit configurer les variables)

---

## Etape 3 : Configurer les variables d'environnement

Dans ton Web Service, va dans **"Environment"** → **"Add Environment Variable"**.

### Variables Application

| Variable       | Valeur                                                |
|----------------|-------------------------------------------------------|
| `APP_NAME`     | `Trouve Ton Photographe`                              |
| `APP_ENV`      | `production`                                          |
| `APP_KEY`      | `base64:QicYhkqQIE6ojpDVYLShO+FLE0jC+U0wCzNeudfhow4=` |
| `APP_DEBUG`    | `false`                                               |
| `APP_TIMEZONE` | `Europe/Paris`                                        |
| `APP_URL`      | `https://photomatch.onrender.com`                     |
| `APP_LOCALE`   | `fr`                                                  |

### Variables Base de donnees (Supabase)

| Variable        | Valeur                                    |
|-----------------|-------------------------------------------|
| `DB_CONNECTION` | `pgsql`                                   |
| `DB_HOST`       | `db.xxxxx.supabase.co`                    |
| `DB_PORT`       | `5432`                                    |
| `DB_DATABASE`   | `postgres`                                |
| `DB_USERNAME`   | `postgres`                                |
| `DB_PASSWORD`   | `ton_mot_de_passe_supabase`               |

**Note :** Recupere le host dans Supabase → Project Settings → Database → Host.

### Variables Session/Cache/Queue

| Variable           | Valeur     |
|--------------------|------------|
| `SESSION_DRIVER`   | `database` |
| `CACHE_STORE`      | `database` |
| `QUEUE_CONNECTION` | `database` |
| `LOG_CHANNEL`      | `stderr`   |
| `LOG_LEVEL`        | `warning`  |
| `BCRYPT_ROUNDS`    | `12`       |

### Variables Email (Brevo - gratuit 300 emails/jour)

1. Cree un compte sur **https://www.brevo.com** (gratuit)
2. Va dans **Settings** → **SMTP & API** → **SMTP**
3. Genere une cle SMTP

| Variable            | Valeur                            |
|---------------------|-----------------------------------|
| `MAIL_MAILER`       | `smtp`                            |
| `MAIL_HOST`         | `smtp-relay.brevo.com`            |
| `MAIL_PORT`         | `587`                             |
| `MAIL_USERNAME`     | `ton-email@brevo.com`             |
| `MAIL_PASSWORD`     | `ta-cle-smtp-brevo`               |
| `MAIL_ENCRYPTION`   | `tls`                             |
| `MAIL_FROM_ADDRESS` | `contact@trouvetonphotographe.fr` |
| `MAIL_FROM_NAME`    | `Trouve Ton Photographe`          |

### Variables Stockage S3 (Minio)

Les photos sont stockees sur le serveur Minio existant.

| Variable                      | Valeur                                   |
|-------------------------------|------------------------------------------|
| `FILESYSTEM_DISK`             | `s3`                                     |
| `AWS_ACCESS_KEY_ID`           | `photomatch-user`                        |
| `AWS_SECRET_ACCESS_KEY`       | `(ton secret key Minio)`                 |
| `AWS_DEFAULT_REGION`          | `us-east-1`                              |
| `AWS_BUCKET`                  | `photomatch`                             |
| `AWS_ENDPOINT`                | `https://s3.oceanetorresphotographie.fr` |
| `AWS_URL`                     | `https://s3.oceanetorresphotographie.fr` |
| `AWS_USE_PATH_STYLE_ENDPOINT` | `true`                                   |

**Important :** `AWS_URL` est l'URL publique pour acceder aux images. Verifie que ton bucket Minio est accessible publiquement en lecture.

---

## Etape 4 : Deployer

1. Une fois toutes les variables ajoutees, clique sur **"Manual Deploy"** → **"Deploy latest commit"**
2. Suis les logs dans l'onglet **"Logs"**

Le build prend environ 5-10 minutes.

---

## Etape 5 : Obtenir l'URL publique

Une fois deploye, ton site est accessible sur :

```
https://photomatch.onrender.com
```

(Le nom exact depend du nom de ton service)

---

## Etape 6 : Configurer le domaine personnalise

1. Dans ton Web Service, va dans **"Settings"**
2. Section **"Custom Domains"** → **"Add Custom Domain"**
3. Entre : `trouvetonphotographe.fr`
4. Render te donne un enregistrement a ajouter

Chez ton registrar DNS (OVH, Cloudflare, etc.) :

```
Type: CNAME
Nom: @ (ou vide)
Valeur: photomatch.onrender.com
```

Pour le www :

```
Type: CNAME
Nom: www
Valeur: photomatch.onrender.com
```

Attends la propagation DNS (quelques minutes a quelques heures).

---

## Etape 7 : Verifications post-deploiement

### Tester l'application

1. Ouvre l'URL de ton site
2. Teste l'inscription d'un utilisateur
3. Teste la connexion
4. Verifie que les emails arrivent (regarde les spams)

### Creer un compte admin

Dans Render, va dans ton Web Service → **"Shell"** et execute :

```bash
# Mode interactif (recommande)
php artisan make:admin

# Ou mode direct avec les options
php artisan make:admin --email=admin@example.com --name="Admin" --password=mot_de_passe_securise
```

L'admin peut ensuite se connecter sur `/login` et acceder au panel admin sur `/admin`.

### Verifier les logs

En cas de probleme, consulte les logs dans :

- Onglet **"Logs"** de ton Web Service

---

## Cout estime

| Service        | Cout                                         |
|----------------|----------------------------------------------|
| Render Free    | $0 (750h/mois, sleep apres 15min inactivite) |
| Render Starter | $7/mois (pas de sleep)                       |
| Supabase       | $0 (500MB, 2 projets gratuits)               |
| Minio S3       | $0 (deja heberge)                            |
| Brevo          | $0 (300 emails/jour)                         |
| **Total**      | **$0 - $7/mois**                             |

**Note :** Le plan Free de Render met le service en veille apres 15 minutes d'inactivite. Le premier visiteur apres une periode d'inactivite attendra ~30 secondes le temps que le service redemarre.

---

## Commandes utiles (via Shell Render)

```bash
# Voir le statut des migrations
php artisan migrate:status

# Executer les migrations manuellement
php artisan migrate --force

# Vider les caches
php artisan optimize:clear

# Relancer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Creer un admin
php artisan make:admin
```

---

## Troubleshooting

### Erreur 500

1. Verifie que `APP_KEY` est defini
2. Verifie les logs dans l'onglet **"Logs"**
3. Verifie la connexion a la base de donnees

### Base de donnees non connectee

1. Verifie `DATABASE_URL` dans les variables d'environnement
2. Verifie que le mot de passe dans l'URL est correct (pas de `[YOUR-PASSWORD]`)
3. Utilise le port `6543` (pooler) et non `5432`
4. Dans Supabase, verifie que le projet n'est pas en pause (inactivite)

### Assets CSS/JS non charges

1. Verifie que le build a reussi (cherche "npm run build" dans les logs)
2. Verifie que `APP_URL` correspond a ton domaine

### Service lent au demarrage

C'est normal sur le plan Free - le service dort apres 15 min d'inactivite.
Solution : passer au plan Starter ($7/mois) ou utiliser un service de ping externe.

### Emails non recus

1. Verifie les credentials SMTP Brevo
2. Regarde dans les spams
3. Teste avec une autre adresse email

---

## Backup de la base de donnees

### Supabase

Supabase fait des backups automatiques quotidiens (7 jours de retention sur le plan gratuit).

Pour un backup manuel :

1. Va dans ton projet Supabase → **Database** → **Backups**
2. Ou utilise `pg_dump` depuis le Shell Render :

```bash
pg_dump $DATABASE_URL > backup.sql
```

---

## Mettre a jour l'application

Chaque `git push` sur la branche main declenche automatiquement un nouveau deploiement.

```bash
git add .
git commit -m "Update"
git push origin main
```

Render reconstruit et redeploy automatiquement.

---

## Configuration avancee (optionnel)

### render.yaml (Infrastructure as Code)

Tu peux creer un fichier `render.yaml` a la racine pour automatiser la configuration :

```yaml
services:
    -   type: web
        name: photomatch
        runtime: docker
        repo: https://github.com/ton-username/photomatch
        branch: main
        healthCheckPath: /
        envVars:
            -   key: APP_ENV
                value: production
            -   key: APP_KEY
                sync: false
            # ... autres variables
```

Voir la documentation Render pour plus de details.
