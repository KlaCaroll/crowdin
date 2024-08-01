# CROWDIN

## Symphony Version

Vous devez avoir la version 7.0.0 de symfony au minimum

## Récupération du contenu de crowdin  

Lancer la commande:
```composer install```
cela vous permet d'avoir un environement avec toutes les extensions demandées

## Environement packages

Lancer l'installation de php mysql si vous l'avez pas.
```sudo apt-get update```
```sudo apt-get install php-mysql```
```sudo apt-get update```
```sudo apt-get upgrade```

## Vérification de php mysql 

Pour savoir si php msql est déjà installé lancer vérifiez avec la commande suivante:
```php -m ```
Cela vous montre toutes les extentions de php déjà installées
Il faut faut aussi avoir les extention suivantes : 
-Ctype
-iconv
-PCRE
-Session
-SimpleXML
-Tokenizer

## Configuration du fichier .env

Dans le fichier .env, pensez à mettre à jour votre URL de base de données.
Pour ce faire saisir la commande:
```mysql -u ... -p ...```
```status;```
Vous y trouverez vos informations à remplir
```DATABASE_URL="mysql://user:password@127.0.0.1:3306/crowdin?serverVersion=10.11.6-MariaDB-0+deb12u1"```


## Création de la base de donnée 

Lancer la commande suivante:
```php bin/console doctrine:database:create ```
Cette commande va creer la DB rensignée dans le lien de connexion (pour nous c'est crowdin)
La base de donnée doit être crée sans erreurs 

## Migration des tables 

Saisir:
demarrer un instance de migration
```php bin/console make:migration```
appliquer les migrations vers la DB
```php bin/console doctrine:migrations:migrate```
afin de lancer la migrations des tables.
Puis remplir la DB avec des données initiales qui seront utiles pour la création des controllers 
Lancer la commande:
```php bin/console doctrine:fixtures:load ```   

## Lancement du server

Saisir la commande:
```php -S localhost:8000 -t public```
Pour arrêter le server:
ctrl+C