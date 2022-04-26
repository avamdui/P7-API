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

{
    "username": "avamdui",
    "password": "Test007"
}
{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NTA5NzU0NDgsImV4cCI6MTY1NDU3NTQ0OCwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6ImF2YW1kdWkifQ.CFhi0h27B1eGReTm55CpA3XRIWUMgUOI7GH5k1ECK8STji-fktWhkT9M0GGbcrYRCUf-w_r0mM-AtYABdcT2OdgkbCnER3z6gBAkHGX0N-ChCVVODXI6JM4NSEKiOEProgVK7LS-gq_x_vTOy1_k0TBKeP45aEBR_S1AugzqZn1S8xKZ2BvY8TWZ0dlhBtdBwAKFhmQvTrZkfwe2FfBvbWVol-SHlE2Kbn10lJr8JHxEjyB3oJ_FsqWToMYR28s4cph_UlIabglYtj5thryiaZ_EjkeycLWBs_2FMgonSK7BQcwerZsyY2MW7kyKxQzq3RtP-Rgm7RiUZ-Facf72xg"}