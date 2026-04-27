# Gestion Licence

Gestion Licence est une application web de gestion (ERP) du pôle bachelôr au sein du Lycée Saint Vincent à Senlis.

En tant qu'étudiants en 2ème année de bts SIO, nous avons du réalisé ce projet de fin d'année donné par le directeur technique d'une entreprise.

- Travail d'équipe en binome. Fonctionnement en méthode agile avec un sprint review tout les lundis.
- Réalisation d'un backlog et d'une roadmap avec GitHub afin de suivre les sprints et de répartir les issues 
- Communication et suivi à l'aide de logiciels comme slack et en présenciel plusieurs jours par semaine.


## Installation

```bash
# Cloner le projet
git clone https://github.com/Diegoodlvv/Gestion_Licence
cd Gestion_Licence

# Installer les dépendances
composer install
npm install

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

- Gestion des enseignants
- Création et suivi des interventions
- Organisation par blocs d'enseignement et années scolaires
- Gestion des modules de cours 
- Calendrier des cours
- Export de données du calendrier et du personnel 

## Stack

- **Backend**: Symfony 8, Doctrine ORM, PHP 8.5
- **Frontend**: Tailwind CSS, Stimulus, FullCalendar, Tom Select
- **Base de données**: MySQL 
- **Build**: Webpack Encore

## Documentations 

- [Guide Utilisateur](GuideUtilisateur(1).pdf)
- [Modèle EA, outils et backlog](Documentation&Outils.pdf)

