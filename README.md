# GestionÉtudiants

Application web moderne de gestion des étudiants et des filières développée avec PHP, MySQL (TiDB Cloud) et Bootstrap.

## Fonctionnalités

- **Gestion des Étudiants**
  - Ajouter, modifier et supprimer des étudiants
  - Gérer les informations personnelles
  - Affichage en liste avec vue détaillée
  - UI moderne "Glassmorphism"

- **Gestion des Filières**
  - Création et gestion des filières
  - Association des étudiants aux filières
  - Vue d'ensemble des effectifs

- **Système d'Authentification**
  - Inscription et connexion sécurisée
  - Gestion des profils utilisateurs (Administrateur / Invité)

## Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur (ou TiDB)
- Apache 2.4 ou supérieur
- Docker (Optionnel, pour le déploiement)
- Extension PHP : `pdo_mysql`

## Installation en Local

1. **Cloner le projet**

   ```bash
   git clone https://github.com/ReedBelca10/students-management.git
   cd students-management
   ```

2. **Configuration de la base de données**
   - Créer une base de données MySQL
   - Importer le fichier SQL :
     ```bash
     mysql -u votre_utilisateur -p votre_base < db/schema.sql
     ```
   - Configurer les accès dans `pages/config/db.php` ou via les variables d'environnement (`DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`).

3. **Démarrage**
   - Placez le dossier dans `htdocs` ou `www` de votre serveur local (WAMP/XAMPP).
   - Accédez à l'application via : `http://localhost/students-management/`

## Déploiement en Production (Render & Docker)

Ce projet est configuré pour être déployé facilement sur [Render](https://render.com/) à l'aide de Docker.

1. Créez un **Web Service** sur Render lié à votre dépôt GitHub.
2. Choisissez **Docker** comme environnement d'exécution (Render détectera automatiquement le fichier `Dockerfile`).
3. Ajoutez les **Variables d'environnement** requises pour la connexion à la base de données de production (ex: TiDB Cloud) :
   - `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`
4. Cliquez sur **Deploy**.

## Compte par défaut (Admin)

- Email : testneyla@gmail.com
- Nom utilisateur : Reed1020
- Mot de passe : 1234Reed$

## Technologies utilisées

- **Frontend**
  - HTML5 / CSS3 (Design Glassmorphism, animations)
  - JavaScript
  - Bootstrap 5
  - Font Awesome

- **Backend & Infrastructure**
  - PHP 8.2
  - MySQL / TiDB Cloud (Base de données)
  - Docker (Conteneurisation)
  - Render (Hébergement web)

## Sécurité Avancée

- Protection contre les Injections SQL (via PDO et requêtes préparées)
- Hachage sécurisé des mots de passe (`password_hash`)
- Échappement des données (XSS) avec `htmlspecialchars`
- Protection contre les failles CSRF avec jetons aléatoires sur tous les formulaires
- Protection des dossiers sensibles et entêtes de sécurité via `.htaccess`

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Auteurs

- **ReedBelca** - _Développement_
