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
{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NTA5ODAxMDQsImV4cCI6MTY1NDU4MDEwNCwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6ImF2YW1kdWkifQ.lb_vGTFVfl8NuhIVoHtyGnoZ9uUjNkO8u-F57aZZCW4TmzYn39Hoo76Dx6Fq5t6xGyggkUym5HJxImcuEhx9OZ4vg2-i9AP6oBnmkCtcrMl0gr94btB0kIY5GEbIY1FR46CFvEUyX50Uq5aVaFP9hl6iIK_BSoDxeeq4SDlOHMTK8rQ-GMqlSCNGqWWwyMiReap1A_zdOyZVcR9fFEQycV_BiJxJQRZEKaYD77QODkQtKogjnnxgM8w1RoVjBBaUqrQJ_8tU2TFRyBjUaGKF38o6lTK_juKDx4Bbk3TJEQLt3sJL_R7i-HjH_lP9_ml1KRo1l5La_h7ydKf2LcIN3g"}