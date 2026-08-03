# GestionÉtudiants

Application web de gestion des étudiants et des filières développée avec PHP, MySQL et Bootstrap.

## Fonctionnalités

- **Gestion des Étudiants**
  - Ajouter, modifier et supprimer des étudiants
  - Gérer les informations personnelles
  - Upload de photos de profil
  - Affichage en liste avec filtrage

- **Gestion des Filières**
  - Création et gestion des filières
  - Association des étudiants aux filières
  - Vue d'ensemble des effectifs

- **Système d'Authentification**
  - Inscription et connexion sécurisée
  - Réinitialisation de mot de passe par email
  - Gestion des profils utilisateurs
  - Protection contre les injections SQL

## Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Apache 2.4 ou supérieur
- Composer (pour les dépendances PHP)
- Extension PHP :
  - mysqli
  - pdo_mysql
  - openssl
  - fileinfo
  - gd

## Installation

1. **Cloner le projet**

   ```bash
   git clone https://github.com/votre-username/Gestion_Etudiants.git
   cd Gestion_Etudiants
   ```

2. **Installer les dépendances**

   ```bash
   composer install
   ```

3. **Configuration de la base de données**
   - Créer une base de données MySQL
   - Importer les fichiers SQL dans l'ordre suivant :
     ```bash
     mysql -u votre_utilisateur -p votre_base < db/schema.sql
     mysql -u votre_utilisateur -p votre_base < db/seed.sql
     ```
   - Configurer les accès dans `pages/config/db.php`

4. **Configuration email (pour la réinitialisation de mot de passe)**
   - Modifier les paramètres SMTP dans `pages/config/mail.php`

## Démarrage

1. Assurez-vous que votre serveur Apache est en cours d'exécution
2. Accédez à l'application via votre navigateur :
   ```
   http://localhost/Gestion_Etudiants/
   ```

## Compte par défaut

- **Admin**
  - Email : testneyla@gmail.com
  - Nom utilisateur : Reed1020
  - Mot de passe : 1234Reed$

## Structure du projet

```
Gestion_Etudiants/
 assets/
    css/
    js/
    img/
 db/
    schema.sql
    seed.sql
 includes/
    auth.php
    header.php
    footer.php
 pages/
    config/
 vendor/
 index.php
```

## Technologies utilisées

- **Frontend**
  - HTML5
  - CSS3
  - JavaScript
  - Bootstrap 5
  - Font Awesome
  - Animate.css

- **Backend**
  - PHP 8
  - MySQL
  - PHPMailer

## Sécurité

- Protection contre les injections SQL
- Hachage sécurisé des mots de passe
- Validation des données
- Protection CSRF
- Sessions sécurisées
- Connexion SMTP sécurisée

## Mises à jour régulières

Pour mettre à jour votre installation :

```bash
git pull origin main
composer update
```

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :

1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commit vos changements
4. Push sur votre fork
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Auteurs

- **ReedBelca** - _Développement initial_

## Contact

Pour toute question ou suggestion, n'hésitez pas à me contacter :

- Email : reedbelca55@gmail.com
- GitHub : @ReedBelca10

## Remerciements

- Bootstrap pour le framework CSS
- PHPMailer pour la gestion des emails
- La communauté open source pour son soutien
