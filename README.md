# Plateforme de réservation de ressources :  Laravel API REST + Front Blade/Tailwind 

Ce projet est une plateforme web  de réservations de ressources réalisée avec **Laravel** (API RESTful), front-end en **Blade** et **TailwindCSS**.  
L’interface communique avec l’API via JavaScript (fetch/AJAX), pour une séparation claire front/back, tout en restant full Laravel.
Le projet utilise Docker qui est un outil qui permet de créer un conteneur qui va offrir tout ce que une application a besoin pour pouvoir marche correctement (code, serveur, base de données, etc.).
Les fichiers clés :
Dockerfile : décrit comment construire l’environnement PHP/Laravel.
docker-compose.yml : décrit tous les “services” à démarrer (app, nginx, mysql, redis…).
Il faut au préalable installer docker desktop selon ton système d’exploitation . 

---

## 🚀 Fonctionnalités principales

- **Authentification API** (Inscription/Login/Logout) via Sanctum, token stocké côté navigateur.
- **Gestion des ressources** (CRUD) : créer, afficher, modifier et supprimer des ressources.
- **Réservations** :
  - Créer, afficher, modifier et supprimer ses propres réservations.
  - Admin : voir/tout gérer.
  - Notification par email (Mailtrap en dev) lors de la création, validation ou refus d’une réservation.
- **Front-end Blade** :
  - Les vues Blade consomment l’API via JavaScript.
  - Style moderne avec TailwindCSS.
  - Messages de confirmation (flash) après actions.

---

## 🛠️ Installation & lancement

1. **Cloner le repo**
   ```bash
   git clone https://github.com/lelenta05/projet-de-reservation-de-ressource.git
   cd projet-de-reservation-de-ressource
   ```

2. **Les commandes de base pour docker **
   ```bash
   docker-compose up -d –build
  docker-compose exec app composer install
  docker-compose exec app php artisan key:generate
   ```
3. **Pour lancer la base de donnée**
   ```bash
docker run --rm --network gestion_reservation_laravel -p 8081:8080 adminer

   ```


3. **Configurer l’environnement**
   - Copier `.env.example` en `.env`
   - **Mailtrap** pour le développement :
     ```
     MAIL_MAILER=smtp
     MAIL_HOST=sandbox.smtp.mailtrap.io
     MAIL_PORT=2525
     MAIL_USERNAME=xxxxxxx
     MAIL_PASSWORD=xxxxxxx
     MAIL_ENCRYPTION=null
     MAIL_FROM_ADDRESS="noreply@votreapp.test"
     MAIL_FROM_NAME="Gestion Réservations"
     ```

4. **Migrer la base et créer un admin (facultatif)**
   ```bash
   Dokcer-compose exec app php artisan migrate
   # Facultatif : seed admin/user/ressources
   php artisan db:seed
   ```

5. **Lancer le serveur**
   Avec docker plus besoin de passe la commande php artisan serve pour lance le serveur , vous devez juste ouvrir votre navigateur est lance http://localhost:8080 

---

## 🌐 Usage
-Tester les api avec l’outil Postman
Ou encore :
- Accédez à l’accueil : http://localhost:8080
- **Inscrivez-vous ou connectez-vous** (le token est automatiquement stocké).
- Naviguez entre :
  - Ressources : voir, créer, éditer, supprimer (si admin)
  - Réservations : créer, éditer, supprimer sa réservation, voir le statut
- **Notifications** par email (Mailtrap) lors des actions importantes.

---

## 📦 Structure des fichiers importants

```
resources/views/
├── layouts/app.blade.php   # Layout principal (nav, CSS, etc.)
├── welcome.blade.php       # Page d’accueil
├── dashboard.blade.php     # Dashboard protégé
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── ressources/
│   └── index.blade.php     # CRUD ressource via API
└── reservations/
    └── index.blade.php     # CRUD réservation via API
```

---

## 🧪 Tests

- Les tests d’API et de notification utilisent `Mail::fake()`/`Notification::fake()`.
- Lancer les tests :
  ```bash
  Doker-compose exec app php artisan test
  ```

---

## ✨ Techs utilisées

- [Laravel 11+](https://laravel.com/)
- [TailwindCSS](https://tailwindcss.com/)
- [Mailtrap](https://mailtrap.io/) (dev)
- JavaScript (fetch API) côté Blade
- Sanctum (auth API)
-[Postman] (Tester les api )

---

## 📢 Remarques

- **Aucune donnée sensible** (mot de passe/token) n’apparaît côté front.
- Les routes API sont sécurisées par Sanctum.
- Prêt à être adapté pour un vrai front SPA/Vue/React ultérieurement.

---

## 📝 Auteur

Projet réalisé par [lelenta05 : https://github.com/votre-utilisateur ].
