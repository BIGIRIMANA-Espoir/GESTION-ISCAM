1. DESCRIPTION DU PRODUIT

   
   
1.1. Qu'est-ce que l'application ?

L'application web de gestion académique pour l'ISCAM est une plateforme conçue pour numériser et simplifier l'ensemble des processus académiques de l'institut. Elle permet de gérer efficacement les étudiants, les enseignants, les cours, les inscriptions et les notes dans un environnement centralisé et sécurisé.


1.2. Objectifs du système

L'application vise à :

•	Centraliser la gestion des informations académiques
•	Faciliter les inscriptions annuelles des étudiants
•	Permettre la saisie et la consultation des notes en temps réel
•	Assurer une communication efficace entre l'administration, les enseignants et les étudiants
•	Générer des rapports statistiques pour le suivi pédagogique
•	Garantir la traçabilité de l'historique académique de chaque étudiant


1.3. Fonctionnalités principales

Pour l'Administrateur:
•	Gestion complète des utilisateurs (création, modification, suppression)
•	Gestion des facultés et départements
•	Création et affectation des cours
•	Validation des inscriptions
•	Supervision des notes saisies par les enseignants
•	Génération de rapports statistiques

Pour l'Enseignant:

•	Consultation de ses cours assignés
•	Saisie des notes pour ses étudiants
•	Visualisation des listes d'étudiants par cours

Pour l'Étudiant:

•	Consultation de ses notes et relevés
•	Suivi de son parcours académique
•	Demande d'inscription annuelle


1.4. Public cible

Cette application est destinée à l'ISCAM (Institut Supérieur des Cadres Militaires) et s'adresse à trois types d'utilisateurs :
•	Le personnel administratif
•	Le corps enseignant
•	Les étudiants de l'institut


2. MODÈLE DE CYCLE DE VIE DU DÉVELOPPEMENT : SCRUM

   
2.1. Définition de Scrum


Scrum est un cadre de travail (framework) agile utilisé pour développer, livrer et maintenir des produits complexes. Contrairement aux méthodes traditionnelles qui planifient tout à l'avance, Scrum repose sur l'empirisme : les décisions sont prises sur la base de l'expérience et de l'observation concrète.
Le cycle de vie Scrum est itératif (on répète des cycles) et incrémental (on ajoute des morceaux de produit à chaque cycle). Il s'appuie sur trois piliers :

1.	La Transparence : Tout le monde partage le même langage et les mêmes standards.
2.	L'Inspection : Le produit et les processus sont examinés régulièrement.
3.	L'Adaptation : Si une dérive est constatée, le processus ou le produit est ajusté immédiatement.

   
2.2. Pourquoi avons-nous choisi Scrum ?

Pour le développement de cette application, nous avons opté pour la méthodologie Scrum en raison de sa flexibilité et de son adaptabilité aux besoins changeants du projet. Contrairement aux modèles traditionnels en cascade, Scrum nous a permis d'avancer par étapes tout en intégrant les retours continus.


2.3. Avantages constatés

Cette approche nous a permis de :
•	Livrer rapidement des versions fonctionnelles
•	Adapter les fonctionnalités aux besoins réels
•	Détecter et corriger les problèmes plus tôt
•	Travailler de manière itérative et collaborative


Image Scrum : 


<img width="975" height="470" alt="image" src="https://github.com/user-attachments/assets/84a480be-f2ee-4756-906d-8f6f3f7c67dd" />



 
3. TECHNOLOGIES UTILISÉES

   
3.1. Framework principal : Laravel

Version : Laravel 8.1.25

Laravel est un framework PHP moderne qui suit l'architecture MVC (Modèle-Vue-Contrôleur). Nous l'avons choisi pour:
•	Sa syntaxe élégante et expressive
•	Son système d'authentification intégré
•	Son ORM (Mapping Objet-Relationnel)  une technique qui fait le lien entre les objets du code (classes, modèles) et les tables d’une base de données relationnelle.
•	Ses fonctionnalités de validation et de sécurité
•	Sa communauté active et sa documentation complète



3.2. Base de données : MySQL


Version : MySQL 8.0

MySQL est un système de gestion de base de données relationnelle robuste et performant. Il nous a permis de:
•	Structurer nos données de manière cohérente
•	Établir des relations entre les tables (étudiants, enseignants, cours, notes)
•	Garantir l'intégrité des données
•	Effectuer des requêtes complexes pour les statistiques


3.3. Langages de programmation

Langage	Utilisation
PHP 8.1	Langage principal côté serveur
HTML5	Structure des pages web
CSS3	Mise en forme et design
JavaScript	Interactions dynamiques
SQL	Requêtes vers la base de données




3.4. Environnement de développement

Outil	Utilisation
XAMPP	Serveur local (Apache, MySQL, PHP)
VS Code	Éditeur de code
phpMyAdmin	Gestion visuelle de la base de données
Git	Versionnement du code
GitHub	Hébergement du code et collaboration


3.6. Architecture du système


L'application suit l'architecture MVC (Modèle-Vue-Contrôleur) :


•	Modèles : Représentent les entités (User, Etudiant, Enseignant, Cours, Note, etc.)

•	Vues : Templates Blade pour l'affichage

•	Contrôleurs : Gèrent la logique métier

•	+Services : Encapsulent la logique complexe réutilisable

•	+Middleware : Gèrent la sécurité et les autorisations


3.7. Structure de la base de données

Notre base de données comprend les principales tables suivantes :



•	Users(Utilisateurs) : Comptes utilisateur (admin, enseignant, étudiant)

•	etudiants : Informations spécifiques aux étudiants

•	enseignants : Informations spécifiques aux enseignants

•	facultes : Facultés de l'établissement

•	departements : Départements liés aux facultés

•	cours : Cours enseignés

•	inscriptions : Inscriptions des étudiants aux cours

•	notes : Notes obtenues par les étudiants

•	notifications : Système de notifications

•	annees_academiques : Années académiques


CONCLUSION

L'application de gestion académique pour l'ISCAM a été développée avec succès en utilisant une approche Scrum adaptée aux contraintes du projet. Les technologies choisies (Laravel, MySQL, Bootstrap) garantissent une application robuste, évolutive et facile à maintenir. Le produit final répond aux besoins exprimés et offre une solution complète pour la gestion académique de l'institut.


                                                MERCI !!!
