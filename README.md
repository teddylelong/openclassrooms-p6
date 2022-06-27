# OpenClassrooms - Projet 6

Développez de A à Z le site communautaire SnowTricks

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8d0751cb175e4103be8b02766326d85d)](https://www.codacy.com/gh/teddylelong/openclassrooms-p6/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=teddylelong/openclassrooms-p6&amp;utm_campaign=Badge_Grade)

Ce dépot est un projet étudiant en cours de réalisation dans le cadre de ma formation *Développeur d'Applications PHP/Symfony* avec OpenClassrooms.

## Arborescence du projet

Trois dossiers se trouvent à la racine du projet :
- `php` : Lié à Docker - contient le fichier de configuration vhost, recommandé par Symfony
- `snow_tricks` : Dossier racine du projet web
- `uml` : Contient l'ensemble des diagrammes UML relatifs au projet


## Comment l'installer ?

### Pré-requis :

- Installez [Docker](https://docs.docker.com/get-docker/)
- Installez [Node.js](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm) (pour utiliser NPM)
- Installez [Composer](https://getcomposer.org/download/)


### 1. Clonez le projet 

Depuis le terminal de votre ordinateur, utilisez la commande suivante afin de copier 
l'intégralité des fichiers du projet dans le dossier de votre choix :

```
cd {chemin/vers/le/projet}
git clone https://github.com/teddylelong/openclassrooms-p6.git
```


### 2. Installation des dépendances

Gardez votre terminal ouvert, toujours positionné sur le dossier du projet, et lancez désormais les deux commandes suivantes.

- La première permettra d'installer Symfony et l'ensemble de ses dépendances :

```
cd {chemin/vers/le/projet}/openclassrooms-p6/snow_tricks/
composer install
```

- La seconde commande permet d'installer Bootstrap au sein du projet. 
Positionnez-vous d'abord dans le bon dossier puis lancez l'installation :

```
cd {chemin/vers/le/projet/}openclassrooms-p6/snow_tricks/public/assets
npm install
```


### 3. Initialisez les conteneurs Docker

Depuis le dossier racine du projet, lancez la commande suivante :

```
cd {chemin/vers/le/projet/}openclassrooms-p6/
docker-compose up
```

Afin de prévenir de potentiels problèmes de droits d'accès, éxecutez juste après cette commande :
```
sudo chown -R $USER ./
```

### 4. Création de la base de données

Nous allons désormais lancer les commandes directement depuis le conteneur Docker, pour des raisons
pratiques. Éxecutez donc les commandes suivantes afin d'initialiser la base de données :

```
docker exec -it st_www bash
cd snow_tricks/
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate
```
Validez en saisissant « y ». La base de données est à présent prête !

### 5. Mise en place des Fixtures

Une fois l'initialisation terminée, lancez la commande suivante afin de charger un jeu
d'enregistrements fictifs (Fixtures) :

```
cd {chemin/vers/le/projet}/openclassrooms-p6/snow_tricks/
php bin/console doctrine:fixtures:load
```
L'opération peut durer plus d'une minute.

### Fin de l'installation

Le projet est à présent installé !

- Vous devriez pouvoir le tester en vous rendant sur http://localhost:8000. 
- Accédez à PHPMyAdmin via http://localhost:8080 
- Consultez les emails reçus avec l'aide de MailDev via http://localhost:8081.

## Comptes utilisateurs

Afin de pouvoir tester le site-web et ses fonctionnalités, sont mis à disposition trois comptes utilisateurs
qui disposent chacun d'un rôle différent. Utilisez-les comme bon vous semble.

Rendez-vous sur la [page de connexion du projet](http://localhost:8000/login) et saisissez l'un des
identifiants ci-dessous :

| Nom d'utilisateur | Mot de passe | Rôle       |
|-------------------|--------------|------------|
| admin             | admin        | ROLE_ADMIN |
| modo              | modo         | ROLE_MODO  |
| user              | user         | ROLE_USER  |