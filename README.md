# OpenClassrooms - Projet 6

Développez de A à Z le site communautaire SnowTricks

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8d0751cb175e4103be8b02766326d85d)](https://www.codacy.com/gh/teddylelong/openclassrooms-p6/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=teddylelong/openclassrooms-p6&amp;utm_campaign=Badge_Grade)

Ce dépot est un projet étudiant en cours de réalisation dans le cadre de ma formation *Développeur d'Applications PHP/Symfony*.


## Comment l'installer ?

### Pré-requis :

- Installez [Docker](https://docs.docker.com/get-docker/)
- Installez [Node.js](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm) (pour utiliser NPM)


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

### 4. Mise en place de la base de données

Une fois l'initialisation terminée, rendez-vous sur http://localhost:8080.

Utilisez le nom d'utilisateur `root` puis laissez vide le champ du mot de passe afin de
vous connecter.

Créez ensuite une nouvelle base de données avec comme nom `snowtricks` et comme interclassement
`utf8mb4_unicode_ci`.

Depuis cette base de données, cliquez ensuite sur "Importer", puis sous "Fichier à importer" 
sélectionnez le fichier `snowtricks.sql` situé à la racine du projet.

### Fin de l'installation

Le projet est à présent installé !

- Vous devriez pouvoir le tester en vous rendant sur http://localhost:8000. 
- Accédez à PHPMyAdmin via http://localhost:8080 
- Consultez les emails envoyés et reçus avec l'aide
de MailDev via http://localhost:8081.

## Comptes utilisateurs

Afin de pouvoir tester le site-web et ses fonctionnalités, sont mis à disposition trois comptes utilisateurs
qui disposent chacun d'un rôle différent. Utilisez-les comme bon vous semble.

Rendez-vous sur la [page de connexion du projet](◊http://localhost:8000/login) et saisissez l'un des
identifiants ci-dessous :

| Nom d'utilisateur | Mot de passe | Rôle       |
|-------------------|--------------|------------|
| Admin             | 12345678     | ROLE_ADMIN |
| Modo              | 12345678     | ROLE_MODO  |
| User              | 12345678     | ROLE_USER  |