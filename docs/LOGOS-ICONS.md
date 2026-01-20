# Guide des Logos et Icones - Trouve Ton Photographe

Ce document liste tous les fichiers d'images necessaires pour le referencement et l'affichage optimal sur les differentes plateformes.

## Emplacement des fichiers

Tous les fichiers doivent etre places dans le dossier `public/` a la racine du projet.

```
public/
├── favicon.ico
├── favicon.png
├── favicon-16x16.png
├── favicon-32x32.png
├── apple-touch-icon.png
├── android-chrome-192x192.png
├── android-chrome-512x512.png
├── mstile-150x150.png
├── safari-pinned-tab.svg
├── site.webmanifest
└── images/
    ├── logo.png
    ├── logo.svg
    ├── logo-white.png
    ├── logo-white.svg
    └── og-default.jpg
```

---

## Favicons (Icones navigateur)

| Fichier | Taille | Format | Utilisation |
|---------|--------|--------|-------------|
| `favicon.ico` | 48x48 (multi-resolution) | ICO | Navigateurs anciens, onglets |
| `favicon.png` | 32x32 | PNG | Fallback moderne |
| `favicon-16x16.png` | 16x16 | PNG | Onglets navigateur |
| `favicon-32x32.png` | 32x32 | PNG | Onglets navigateur HD |

### Specifications
- **Fond** : Transparent ou couleur de marque (#10b981 emerald)
- **Format** : PNG 32-bit avec transparence
- **Contenu** : Icone/symbole simplifie (pas le nom complet)

---

## Apple Touch Icons (iOS)

| Fichier | Taille | Format | Utilisation |
|---------|--------|--------|-------------|
| `apple-touch-icon.png` | 180x180 | PNG | Icone sur ecran d'accueil iOS |

### Specifications
- **Fond** : Couleur pleine (pas transparent) - recommande #10b981
- **Coins** : Carres (iOS arrondit automatiquement)
- **Padding** : ~20px de marge interne
- **Format** : PNG 24-bit sans transparence

---

## Android / PWA

| Fichier | Taille | Format | Utilisation |
|---------|--------|--------|-------------|
| `android-chrome-192x192.png` | 192x192 | PNG | Icone Android / PWA |
| `android-chrome-512x512.png` | 512x512 | PNG | Splash screen PWA |

### Specifications
- **Fond** : Peut etre transparent ou avec couleur
- **Format** : PNG 32-bit
- **Contenu** : Logo complet ou icone

---

## Microsoft / Windows

| Fichier | Taille | Format | Utilisation |
|---------|--------|--------|-------------|
| `mstile-150x150.png` | 150x150 | PNG | Tuile Windows |

### Specifications
- **Fond** : Transparent (la couleur est definie dans browserconfig.xml)
- **Format** : PNG 32-bit avec transparence

---

## Safari (macOS)

| Fichier | Taille | Format | Utilisation |
|---------|--------|--------|-------------|
| `safari-pinned-tab.svg` | N/A | SVG | Onglet epingle Safari |

### Specifications
- **Couleur** : Noir uniquement (#000000)
- **Format** : SVG vectoriel
- **Contenu** : Silhouette monochrome du logo

---

## Open Graph / Reseaux sociaux

| Fichier | Taille | Format | Utilisation |
|---------|--------|--------|-------------|
| `images/og-default.jpg` | 1200x630 | JPG | Partage Facebook, LinkedIn |

### Specifications
- **Ratio** : 1.91:1 (1200x630 pixels)
- **Format** : JPG (pas PNG pour reduire la taille)
- **Qualite** : 80-90%
- **Contenu** : Logo + nom du site + tagline eventuellement
- **Taille max** : < 300 Ko

---

## Logos du site

| Fichier | Taille recommandee | Format | Utilisation |
|---------|-------------------|--------|-------------|
| `images/logo.png` | 400x100 | PNG | Header, emails |
| `images/logo.svg` | Vectoriel | SVG | Header (meilleure qualite) |
| `images/logo-white.png` | 400x100 | PNG | Footer sombre |
| `images/logo-white.svg` | Vectoriel | SVG | Footer sombre |

---

## Fichier Manifest (PWA)

Creer le fichier `public/site.webmanifest` :

```json
{
    "name": "Trouve Ton Photographe",
    "short_name": "TrouveTonPhoto",
    "description": "Trouvez le photographe ideal pour vos projets",
    "start_url": "/",
    "display": "standalone",
    "background_color": "#ffffff",
    "theme_color": "#10b981",
    "icons": [
        {
            "src": "/android-chrome-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/android-chrome-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
```

---

## Balises HTML a ajouter

Ces balises sont deja configurees dans `resources/views/layouts/app.blade.php` mais voici la reference complete :

```html
<!-- Favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/x-icon" href="/favicon.ico">

<!-- Apple -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

<!-- Android / PWA -->
<link rel="manifest" href="/site.webmanifest">

<!-- Microsoft -->
<meta name="msapplication-TileColor" content="#10b981">
<meta name="msapplication-TileImage" content="/mstile-150x150.png">

<!-- Safari -->
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#10b981">

<!-- Theme -->
<meta name="theme-color" content="#10b981">
```

---

## Outils recommandes

1. **Generateur de favicons** : [realfavicongenerator.net](https://realfavicongenerator.net)
   - Uploadez votre logo en haute resolution (512x512 minimum)
   - L'outil genere tous les fichiers necessaires

2. **Compression d'images** : [squoosh.app](https://squoosh.app)
   - Pour optimiser og-default.jpg

3. **Editeur SVG** : [Figma](https://figma.com) ou [Inkscape](https://inkscape.org)
   - Pour creer safari-pinned-tab.svg

---

## Checklist avant mise en production

- [ ] `favicon.ico` (48x48 multi-res)
- [ ] `favicon-16x16.png`
- [ ] `favicon-32x32.png`
- [ ] `apple-touch-icon.png` (180x180)
- [ ] `android-chrome-192x192.png`
- [ ] `android-chrome-512x512.png`
- [ ] `mstile-150x150.png`
- [ ] `safari-pinned-tab.svg`
- [ ] `site.webmanifest`
- [ ] `images/og-default.jpg` (1200x630)
- [ ] `images/logo.png`
- [ ] `images/logo.svg` (optionnel)

---

## Couleurs de reference

| Nom | Hex | Utilisation |
|-----|-----|-------------|
| Emerald 500 | `#10b981` | Couleur principale |
| Emerald 600 | `#059669` | Hover states |
| Emerald 700 | `#047857` | Accents fonces |
| White | `#ffffff` | Fond clair |
| Gray 900 | `#111827` | Fond sombre |
