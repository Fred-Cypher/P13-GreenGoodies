# GreenGoodies - Site Web + API
___
## Description générale :
Projet du parcours "Développeur d'application PHP Symfony" d'OpenClassrooms.

Le titre complet du projet est : **Mettez en place un site de e-commerce avec Symfony**.
Le sujet du projet est la création, à l'aide de Symfony, du site d'une boutique lyonnaise spécialisée dans la vente de produits biologiques, éthiques et écologiques, ainsi que d'une API accessible par les utilisateurs qui le souhaitent.

Création de la partie front en suivant la maquette fournie créée par un UX Designer, et de la partie back à l'aide de Symfony

### Fonctionnalités à mettre en place :
* Visualisation les produits
* Inscription, connexion et suppression des utilisateurs
* Création et enregistrement de commandes
* Affichage du récapitulatif des commandes dans le profil de l'utilisateur
* Utilisation de l'API par les utilisateurs autorisés
___
## Installation :
* Clonez le projet.
* Dans un terminal, placez-vous dans le dossier, utilisez la commande ```composer install``` pour installer les dépendances nécessaires.
* Créez une base de données avec la commande ```php bin/console doctrine:database:create```, puis ```php bin/console doctrine:migrations:migrate``` pour exécuter les migrations.
* Pour créer les données, vous pouvez utiliser les DataFixtures présentes dans le code en utilisant la commande ```php bin/console doctrine:fixtures:load```
* Créez un utilisateur pour avoir accès à toutes les pages et pouvoir ajouter des produits dans le panier et créer une commande 
* Pour l'utilisation de JWT, il est nécessaire de créer une paire de clés (privée et public), le faire avec la commande  ```php bin/console lexik:jwt:generate-keypair``` dans une console bash
* Dupliquez le fichier .env en le renommant .env.local et modifiez les variables : DATABASE_URL et JWT_PASSPHRASE pour entrer vos valeurs.
___
## Utilisation :
* Dans votre IDE, lancez le serveur local avec la commande ```symfony serve```.
* Vous pouvez tester les différentes routes de l'API avec Postman ou Insomnia, en n'oubliant pas de renseigner le token sur la route "api/products" avec le token récupéré dans la réponse de la route "api/login".
___
## Outils / logiciels nécessaires
* WAMP, XAMP, LAMP ou équivalent
* Postman, Insomnia ou équivalent
* IDE : VSC, PhpStorm... 
---

Projet codé avec PHP8.2 et Symfony7.3
