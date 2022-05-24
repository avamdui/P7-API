# P7-API
 Projet 7 OpenClassroom

# Contexte
BileMo est une entreprise offrant toute une sélection de téléphones mobiles haut de gamme.

Vous êtes en charge du développement de la vitrine de téléphones mobiles de l’entreprise BileMo. Le business modèle de BileMo n’est pas de vendre directement ses produits sur le site web, mais de fournir à toutes les plateformes qui le souhaitent l’accès au catalogue via une API (Application Programming Interface). Il s’agit donc de vente exclusivement en B2B (business to business).

Il va falloir que vous exposiez un certain nombre d’API pour que les applications des autres plateformes web puissent effectuer des opérations.

# Besoin client
Le premier client a enfin signé un contrat de partenariat avec BileMo ! C’est le branle-bas de combat pour répondre aux besoins de ce premier client qui va permettre de mettre en place l’ensemble des API et de les éprouver tout de suite.

 Après une réunion dense avec le client, il a été identifié un certain nombre d’informations. Il doit être possible de :

consulter la liste des produits BileMo ;
consulter les détails d’un produit BileMo ;
consulter la liste des utilisateurs inscrits liés à un client sur le site web ;
consulter le détail d’un utilisateur inscrit lié à un client ;
ajouter un nouvel utilisateur lié à un client ;
supprimer un utilisateur ajouté par un client.
Seuls les clients référencés peuvent accéder aux API. Les clients de l’API doivent être authentifiés via OAuth ou JWT.

Vous avez le choix entre mettre en place un serveur OAuth et y faire appel (en utilisant le FOSOAuthServerBundle), et utiliser Facebook, Google ou LinkedIn. Si vous décidez d’utiliser JWT, il vous faudra vérifier la validité du token ; l’usage d’une librairie est autorisé.

# Présentation des données
Le premier partenaire de BileMo est très exigeant : il requiert que vous exposiez vos données en suivant les règles des niveaux 1, 2 et 3 du modèle de Richardson. Il a demandé à ce que vous serviez les données en JSON. Si possible, le client souhaite que les réponses soient mises en cache afin d’optimiser les performances des requêtes en direction de l’API.

## Installation

### Prerequisites

Installer GitHub (<https://gist.github.com/derhuerst/1b15ff4652a867391f03>) \
Installer Composer (<https://getcomposer.org>) \
Installer  Postman  (<https://postman.com/downloads>)

Nécéssite PHP 8 et Symfony 6 


### Download

```shell
git clone https://github.com/avamdui/P7-API.git
```

Installez les dépendances en exécutant la commande suivante :

```shell
composer install
```

### Base de donnée et configuration de (JSON Web Token)) 

Générez les clés SSH avec votre propre phrase secrète :

```shell
$ mkdir -p config/jwt
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

 Personnaliser le fichier .env en renseignant "DATABASE_URL" et "MAILER_DSN"  et Renseigner les Clés Public et Private dans les dossiers 
 %kernel.project_dir%/config/jwt/private.pem
 %kernel.project_dir%/config/jwt/public.pem

```shell
DATABASE_URL="URL DE VOTRE BASE DE DONNEE"
JWT_PASSPHRASE=#your passphrase
```

Creation de la BDD :

```shell
php bin/console doctrine:database:create
```

Créez les différentes tables de la base de données en appliquant les migrations

```shell
php bin/console doctrine:migrations:migrate
```

Installer les fixtures pour avoir une démo de données fictives :

```shell
php bin/console doctrine:fixtures:load
```
###
Pour obtenir un Token, utiliser la méthode POST sur l'url 
http://localhost/api/login_check
{
    "username": "BilemoAdmin",
    "password": "Admin1"
}
