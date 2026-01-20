# Deploiement sur Railway - Trouve Ton Photographe

Guide pas-a-pas pour deployer l'application sur Railway.

---

## Pre-requis

- [ ] Compte GitHub avec le code source pousse
- [ ] Compte Railway (https://railway.app) - connecte avec GitHub

---

## Etape 1 : Creer le projet Railway

1. Va sur **https://railway.app**
2. Clique sur **"New Project"**
3. Selectionne **"Deploy from GitHub repo"**
4. Autorise Railway a acceder a ton repo si necessaire
5. Selectionne le repo **photomatch**

Railway va detecter le `Dockerfile` et commencer le build (il va echouer car pas de base de donnees, c'est normal).

---

## Etape 2 : Ajouter MySQL

1. Dans ton projet Railway, clique sur **"+ New"** (en haut a droite)
2. Selectionne **"Database"** → **"MySQL"**
3. Attends que MySQL soit provisionne (1-2 minutes)

Railway cree automatiquement les variables :
- `MYSQL_HOST`
- `MYSQL_PORT`
- `MYSQL_DATABASE`
- `MYSQL_USER`
- `MYSQL_PASSWORD`

---

## Etape 3 : Lier MySQL a l'application

1. Clique sur ton service **photomatch** (pas MySQL)
2. Va dans l'onglet **"Variables"**
3. Clique sur **"Add Reference"** → **"Add Reference"**
4. Tu verras les variables MySQL disponibles - clique sur chacune pour les ajouter :
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_DATABASE`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`

---

## Etape 4 : Configurer les variables d'environnement

Dans l'onglet **"Variables"** de ton service, ajoute ces variables une par une.

### Variables obligatoires

Clique sur **"New Variable"** pour chaque variable :

| Variable | Valeur |
|----------|--------|
| `APP_NAME` | `Trouve Ton Photographe` |
| `APP_ENV` | `production` |
| `APP_KEY` | `base64:QicYhkqQIE6ojpDVYLShO+FLE0jC+U0wCzNeudfhow4=` |
| `APP_DEBUG` | `false` |
| `APP_TIMEZONE` | `Europe/Paris` |
| `APP_URL` | `https://${{RAILWAY_PUBLIC_DOMAIN}}` |
| `APP_LOCALE` | `fr` |
| `DB_CONNECTION` | `mysql` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `LOG_CHANNEL` | `stderr` |
| `LOG_LEVEL` | `warning` |
| `BCRYPT_ROUNDS` | `12` |

### Variables Email (Brevo - gratuit 300 emails/jour)

1. Cree un compte sur **https://www.brevo.com** (gratuit)
2. Va dans **Settings** → **SMTP & API** → **SMTP**
3. Genere une cle SMTP

| Variable | Valeur |
|----------|--------|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp-relay.brevo.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | `ton-email@brevo.com` |
| `MAIL_PASSWORD` | `ta-cle-smtp-brevo` |
| `MAIL_ENCRYPTION` | `tls` |
| `MAIL_FROM_ADDRESS` | `contact@trouvetonphotographe.fr` |
| `MAIL_FROM_NAME` | `Trouve Ton Photographe` |

### Variables Stockage S3 - Cloudflare R2 (OBLIGATOIRE)

Les photos sont stockees sur Cloudflare R2 (compatible S3, 10GB gratuit).

**Configuration de Cloudflare R2 :**

1. Cree un compte sur **https://dash.cloudflare.com** (gratuit)
2. Va dans **R2 Object Storage** dans le menu de gauche
3. Clique sur **"Create bucket"**
4. Nomme ton bucket `photomatch`
5. Cree une **API Token** :
   - Va dans **Manage R2 API Tokens**
   - Clique **"Create API token"**
   - Permissions : **Object Read & Write**
   - Scope : **Apply to specific bucket(s) only** → `photomatch`
   - Note l'**Access Key ID** et le **Secret Access Key**
6. Configure l'acces public :
   - Clique sur ton bucket `photomatch`
   - Va dans l'onglet **"Settings"**
   - Section **"Public access"** → Active **"r2.dev subdomain"**
   - Note l'URL publique : `https://pub-XXXXX.r2.dev`

| Variable | Valeur |
|----------|--------|
| `FILESYSTEM_DISK` | `s3` |
| `AWS_ACCESS_KEY_ID` | `ton_access_key_r2` |
| `AWS_SECRET_ACCESS_KEY` | `ton_secret_key_r2` |
| `AWS_DEFAULT_REGION` | `auto` |
| `AWS_BUCKET` | `photomatch` |
| `AWS_ENDPOINT` | `https://ACCOUNT_ID.r2.cloudflarestorage.com` |
| `AWS_URL` | `https://pub-XXXXX.r2.dev` |
| `AWS_USE_PATH_STYLE_ENDPOINT` | `true` |

**Important :** Remplace `ACCOUNT_ID` par ton Account ID Cloudflare (visible dans l'URL du dashboard) et `pub-XXXXX` par ton sous-domaine R2 public.

---

## Etape 5 : Deployer

1. Une fois toutes les variables ajoutees, Railway redeploy automatiquement
2. Ou clique sur **"Deploy"** → **"Trigger Deploy"**
3. Suis les logs dans l'onglet **"Deployments"**

Le build prend environ 3-5 minutes.

---

## Etape 6 : Obtenir l'URL publique

1. Va dans l'onglet **"Settings"** de ton service
2. Section **"Networking"** → **"Public Networking"**
3. Clique sur **"Generate Domain"**
4. Railway genere une URL du type : `photomatch-production.up.railway.app`

Ton site est maintenant accessible !

---

## Etape 7 : Configurer le domaine personnalise

1. Dans **Settings** → **Public Networking** → **Custom Domain**
2. Entre : `trouvetonphotographe.fr`
3. Railway te donne un enregistrement CNAME a ajouter

Chez ton registrar DNS (OVH, Cloudflare, etc.) :
```
Type: CNAME
Nom: @ (ou vide)
Valeur: [la valeur donnee par Railway]
```

Pour le www :
```
Type: CNAME
Nom: www
Valeur: [la valeur donnee par Railway]
```

Attends la propagation DNS (quelques minutes a quelques heures).

---

## Etape 8 : Verifications post-deploiement

### Tester l'application

1. Ouvre l'URL de ton site
2. Teste l'inscription d'un utilisateur
3. Teste la connexion
4. Verifie que les emails arrivent (regarde les spams)

### Creer un compte admin

Dans Railway, va dans ton service → onglet **"Shell"** et execute :

```bash
# Mode interactif (recommande)
php artisan make:admin

# Ou mode direct avec les options
php artisan make:admin --email=admin@example.com --name="Admin" --password=mot_de_passe_securise
```

L'admin peut ensuite se connecter sur `/login` et acceder au panel admin sur `/admin`.

### Verifier les logs

En cas de probleme, consulte les logs dans :
- Onglet **"Deployments"** → clique sur un deploiement → **"View Logs"**

---

## Cout estime

| Service | Cout |
|---------|------|
| Railway Hobby | $5/mois (inclut $5 de credits) |
| MySQL | ~$5-10/mois selon usage |
| **Total** | ~$10-15/mois |

Railway facture a l'usage. Le Hobby plan inclut $5 de credits/mois.

---

## Commandes utiles (via Shell Railway)

```bash
# Voir le statut des migrations
php artisan migrate:status

# Vider les caches
php artisan optimize:clear

# Relancer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Voir les logs Laravel
tail -f storage/logs/laravel.log
```

---

## Troubleshooting

### Erreur 500

1. Verifie que `APP_KEY` est defini
2. Verifie les logs
3. Verifie la connexion MySQL

### Base de donnees non connectee

1. Verifie que MySQL est provisionne
2. Verifie que les variables `MYSQL_*` sont bien referencees
3. Dans les logs, cherche "Database not ready"

### Assets CSS/JS non charges

1. Verifie que le build a reussi (cherche "npm run build" dans les logs)
2. Verifie que `APP_URL` est correct

### Emails non recus

1. Verifie les credentials SMTP
2. Regarde dans les spams
3. Teste avec une autre adresse email

---

## Backup de la base de donnees

Railway ne fait pas de backups automatiques sur le plan Hobby.

Options :
1. **Upgrade vers Pro** pour les backups automatiques
2. **Backup manuel** via le Shell :
   ```bash
   mysqldump -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE > backup.sql
   ```

---

## Mettre a jour l'application

Chaque `git push` sur la branche main declenche automatiquement un nouveau deploiement.

```bash
git add .
git commit -m "Update"
git push origin main
```

Railway reconstruit et redeploy automatiquement.
