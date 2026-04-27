# Gestion Licence

Application Symfony 8 pour gérer le pôle license au sein du Lycee Saint Vincent a Senlis, les instructeurs, les interventions et les blocs d'enseignement.

## Fonctionnement

Travail d'equipe en binome. Fonctionnement en methode agile avec un sprint review tout les lundis.

## Installation

```bash
# Cloner le projet
git clone https://github.com/Diegoodlvv/Gestion_Licence
cd Gestion_Licence

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
# Configurer DATABASE_URL dans .env

# Base de données
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load

# Compiler les assets
npm run build
```

## Démarrage

```bash
# Serveur de développement
symfony server:start

# Assets en mode watch
npm run watch
```

## Fonctionnalités

- Gestion des instructeurs et utilisateurs
- Création et suivi des interventions
- Organisation par blocs d'enseignement et années scolaires
- Calendrier des cours
- Export de données (spreadsheet)

## Stack

- **Backend**: Symfony 8, Doctrine ORM
- **Frontend**: Tailwind CSS, Stimulus, FullCalendar, Tom Select
- **Base de données**: PostgreSQL (configuré dans .env)
- **Build**: Webpack Encore
