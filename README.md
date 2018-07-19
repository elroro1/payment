deezer-test-payment
===================


J'ai retenu le choix d'une base postegres et d'un projet symfony pour ce projet

Le script pour créer la base se trouve dans rollout/create_table.sql

Ce test necessite l'utilisation d'un rabbitMQ pour valider la partie asynchrone de l'énnoncé

Pour se faire, j'ai utilisé un container docker avec rabbitMQ 
(et le plugin de management de celui-ci)

Bien evidemment il faut pour prérequis d'avoir docker installé sur son poste

Pour lancer l'image RabbitMQ: 
 
docker run -d --hostname my-rabbit --name some-rabbit-deezer -p 5672:5672 -p 15672:15672 rabbitmq:3-management

On peut ensuite accéder a l'interface RabbitMQ depuis
http://localhost:15672


Pour construire automatiquement les queues et les exchanges, nous pouvons envoyer un message a RabbitMQ
depuis une action symfony

Dans notre cas il faut lancer le serveur web intégré de symfony a la racine du projet
php bin/console server:start 

Puis depuis un navigateur aller sur 
http://127.0.0.1:8000/constructRabbit

Cette action publie juste un message en utilisant la configuration définie dans app/config.yml
 
Vous pouvez dés lors publier les prochains messages directement depuis l'interface de management de RabbitMQ

pour lancer l'execution du process pour les 50 prochains messages, 
il faut lancer le consumer. 
A la racine du projet faire :

php bin/console rabbitmq:consumer -m 50 notification
