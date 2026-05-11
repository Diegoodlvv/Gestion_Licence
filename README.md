<<<<<<< HEAD
[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/JUwsCS71)
=======
# Gestion Licence

Gestion Licence est une application web de gestion (ERP) du pôle bachelôr au sein du Lycée Saint Vincent à Senlis.

En tant qu'étudiants en 2ème année de bts SIO, nous avons du réalisé ce projet de fin d'année donné par le directeur technique d'une entreprise.

- Travail d'équipe en binôme. Fonctionnement en méthode agile avec un sprint review tous les lundis.
- Réalisation d'un backlog et d'une roadmap avec GitHub afin de suivre les sprints et de répartir les issues 
- Communication et suivi à l'aide de logiciels comme slack et en présenciel plusieurs jours par semaine.

## Technologies et versions 

- **Backend**: Symfony 8, Doctrine ORM, PHP 8.5
- **Frontend**: Tailwind CSS, Stimulus, FullCalendar, Tom Select
- **Base de données**: MySQL 
- **Build**: Webpack Encore

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


## Documentations 

- [Guide Utilisateur](GuideUtilisateur.pdf)
- [Modèle EA, outils et backlog](Documentation&Outils.pdf)
-[Figma](https://www.figma.com/design/hcKlxnQhmV2ErZ4n2qUae2/Enseignement-sup%C3%A9rieur?node-id=0-1&p=f&t=lrNyfH5Vv8xfSWpG-0)

## Image du calendrier 

<img width="1916" height="1052" alt="Capture d&#39;écran 2026-02-13 133442" src="https://github.com/user-attachments/assets/3560171a-72aa-48e2-959b-89f131542b97" />

## Image des modules 

<img width="504" height="933" alt="Capture d&#39;écran 2026-01-05 084550" src="https://github.com/user-attachments/assets/0d8f90d5-2669-40c4-8bbe-0ad027318f2f" />
>>>>>>> c9b4e1f1e408da5997c4f841a6542dea9da12018
