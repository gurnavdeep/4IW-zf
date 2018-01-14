ZendSkeletonApplication

Le projet

Cette application a été réalisée sur Zend Framework. 
Le projet comprend notamment les fonctionnalités suivantes :

    Possibilité de créér des meetups

http://localhost:8080/meetup/new

    Affichage de la liste des meetups existants

http://localhost:8080/meetup

    Possibilité de modifier un meetup existant

http://localhost:8080/meetups/update/{id}

    Possibilité de supprimer un meetup existant

http://localhost:8080/meetups/delete/{id}

    Possibilité de consulter le détail d'un meetup existant

http://localhost:8080/meetups/detail/{id}

 
Installation
Pour installer le projet :

Démarrer l'environnement Docker

docker-compose up -d --build

Vérifier les requêtes de création de l'architecture de la base de données.

docker-compose exec zf php vendor/bin/doctrine-module orm:schema-tool:update

Lancer la création de l'architecture de la base de données.

docker-compose exec zf php vendor/bin/doctrine-module orm:schema-tool:update --force

