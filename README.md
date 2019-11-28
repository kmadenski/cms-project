## Running
```bash
docker-compose up -d
docker-compose exec php bin/console doctrine:schema:update --force
docker-compose exec php composer install
```
[Read the official "Getting Started" guide](https://api-platform.com/docs/distribution).
