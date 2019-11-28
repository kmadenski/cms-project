## Running
```bash
docker-compose up -d
docker-compose exec php bin/console doctrine:schema:update --force
docker-compose exec php composer install
```
```
Jak się zalogować?:
Trzeba dodać użytkownika do bazy danych przed tym. Hasło musi być zahashowane. Hash dla hasła można uzyskać poprzez komendę
docker-compose exec php bin/console security:encode-password
https://gist.github.com/kmadenski/f412b9bfdde3c333fa5f6586e77afda9
Jeśli chodzi o admina to z uwagi na brak logowania korzystałem z wtyczki do chrome ModHeader, aby korzystać z admina do czasu implementacji logowania
```
[Read the official "Getting Started" guide](https://api-platform.com/docs/distribution).
