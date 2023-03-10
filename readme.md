# Plateforme d'administration Pvnet 

## Description 

  L'application sert à monitorer les ressources liées à une liste de serveurs et mettre à jour leurs informations système dans une base de données.   
 les certificats ***tls*** des  hôtes virtuels seront vérifiés de façon régulière  et les informations stockés en base.

  <br>

## Technologies utilisées 

langage: ***PHP 8***   
framework: ***Symfony 6.1***  
UI: ***Twig***

 <br>
 
## Mise en place du projet 

### Pré-requis
- installer ***PHP 8***
- installer [***composer***](https://getcomposer.org/)
- installer [***symfony cli***](https://symfony.com/download) (facultatif)
- installer [***symfony***](https://symfony.com/doc/current/setup.html)  
  *pour vérifier que le système peut gérer une application* ***symfony*** *taper dans une console:*
  ```bash
    $ symfony check:requirements
  ```
- installer [***docker***](https://docs.docker.com/get-docker/)

### Installation
  A la racine du projet, pour installer les dépendances, taper dans la console:
  ```
    $ composer install
  ```
  <br>

## Utilisation 
 
 les scripts sont définis dans le fichier ***composer.json***


### Mode développement:
Pour lancer le serveur de développement  
``` 
    $ composer dev-start
```
Pour arrêter le serveur de développement  
```
    $ composer dev-stop
```
Pour initialiser le projet et mettre en place la base de données dans ***docker***   
```
    $ composer dev-start
    $ composer dev-init
```
Pour accéder à ***Adminer*** 
les données de configuration sont dans le fichier *db/docker-composer.yaml*  
***Adminer*** est accessible sur ***localhost:7080***  

Pour accéder au serveur de développement, ouvrir un navigateur, par défaut, il sera accessible sur ***localhost:8000***

<br>

###  Activer le compte d'un utilisateur: 
   lorsqu' un utilisateur s' enregistre, par défaut son compte est inactif.
   l' administrateur doit activer le compte en modifiant en base de données la clef ***is_active*** et la mettre à  **1** dans la table ***User***
   et ajouter le role ***ROLE_ADMIN***  au tableau dans le champ *roles*

<br>

## Configuration 


### configuration SSH pour accéder aux differents serveurs 
fichier de configuration dans *~/$HOME/.ssh*  
créer un ficher config et spécifier la configuration ssh pour chaque serveur  
exemple: 
```
Host vps1
  HostName server1.net
  IdentityFile ~/.ssh/server_rsa
  Port 22
  User userName

  ...
```
le nom associé à la clef *Host* sera utilisé ensuite dans l'application pour lancer des commandes ***ssh*** sur les serveurs distants. Lors de l' ajout d' un serveur dans l' application, mettre le nom de l' hote dans le champ **hote SSH**

installer la clef public *server_rsa.pub* sur les serveurs distants.

<br>

### Changer la configuration de l'application: 
Les differentes constantes sont définies dans le fichier : 

<br>

