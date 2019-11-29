## Running
```bash
docker-compose up -d
docker-compose exec php bin/console doctrine:schema:update --force
docker-compose exec php composer install
```
Przed pierwszym uruchomieniem należy wygenerować parę kluczy
```bash
docker-compose exec php sh -c '
    set -e
    apk add openssl
    mkdir -p config/jwt
    jwt_passhrase=$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')
    echo "$jwt_passhrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    echo "$jwt_passhrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```
Następnie należy zaimportować przykładowego użytkownika i admina do bazy danych.
W plku dump/user.sql znajduje się niezbędna sql-ka

Poniżej przykładowy curl - logowanie: 
https://gist.github.com/kmadenski/f412b9bfdde3c333fa5f6586e77afda9

Admin nie ma zrobionego frontu do trzymania stanu uwierzytelnienia, więc można pobrać token np. postmanem i za pomocą wtyczki ModHeader dodać do każdego zapytania przeglądarki
https://chrome.google.com/webstore/detail/modheader/idgpnmonknjnojddfkpgkljpfnnfcklj

[Read the official "Getting Started" guide](https://api-platform.com/docs/distribution).
