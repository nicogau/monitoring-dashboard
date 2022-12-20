# projet Dashboard d'administration  

## Réunion du 26-11

### Certificats TLS
<br>

À partir d’une liste de noms de domaines, récupérer les certificats pour identifier les registras et les dates d’expiration

un fichier texte contenant la liste des domaines gérés par pointvirgule est lu par le script qui va extraire et analyser le certificat

en sortie un mail  est envoyé pour chaque domaine, on note le registrar et la date de d'expiration du certificat.

<br>

### Informations Serveurs
<br>
Réalisation d'une sonde  permettant de récupérer des informations sur les serveurs de l'infra.

informations à récupérer: 

- version de l'OS
- architecture matériel: cpu/ram/hdd
- version des outils: php/mysql/mariadb/apache
- liste des vhosts installés
- liste de vhosts actifs (ip valide)
- espace mémoire dispo
- état de la sauvegarde

L'idée est de récupérer à distance les informations via une commande SSH.
les informations récupérées peuvent être envoyés par mail ou stockées dans une base de données pour consultation utlérieure via une interface.

Un mail d'alerte peut être envoyé si certaines informations dépassent un seuil critique.
la liste des serveurs à scanner sera fournie dans un fichier texte ou dans une base de données.

## Réunion du  01-12

On ne stocke plus la liste des serveurs et vhosts dans un fichier 

Choix de stocker les informations des ***Vhosts*** et ***Serveurs*** en base de données  

Une interface graphique doit permettre de consulter l'état des machines et d'administrer les différentes listes.

Un contrôle d'accès doit être défini pour autoriser certains utilisateurs à accéder à l'application.

l'application sera certainement installés sur le synology.