####CARAPI

Une api pour rechercher des modèles de voitures parmi des annonces

Pour lancer le projet il vous faut exécuter les commandes suivantes :

`cd docker/carapi && docker compose up -d`

Il vous faudra ensuite charger les librairies:

`cd ../.. && composer install`

Une fois le build de docker executé, il vous faudra créer la base de données ainsi que charger les données nécessaires 
au fonctionnement:

`docker exec -it www_carapi /bin/bash`

`cd carapi`

`symfony console doctrine:database:create`

`symfony console doctrine:schema:update --dump-sql --force`

`symfony console doctrine:fixtures:load`

Voici le fonctionnement des differentes routes:

Exemple de l'appel GET : \
`curl --location --request GET 'http://127.0.0.1:8203/adverts?model=Ds3'`

Exemple de l'appel POST : \
`curl --location --request POST 'http://127.0.0.1:8203/adverts' \
--header 'Content-Type: application/json' \
--data-raw '{
"category":8,
"brand": "Audi",
"model": "Q2",
"title": "Cambriolet Audi à vendre",
"content": "Cambriolet à vendre 8000€"
}'`

Exemple de l'appel PUT : \
`curl --location --request PUT 'http://127.0.0.1:8203/adverts/1' \
--header 'Content-Type: application/json' \
--data-raw '{
"title": "Nouveau titre",
"content": "Cambriolet à vendre 8000€"
}'`

Exemple de l'appel DELETE : \
`curl --location --request PUT 'http://127.0.0.1:8203/adverts/1'`