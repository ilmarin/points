# Points API

Simple example of API that allows to store some interesting places with their map coordinates and description.

With this API you can:

* Add Point info (name, description, coordinates, city)
* Edit Point info
* Get nearest Points by desired radius and IP address
* Get Points located in desired city

## Installation

1. In project folder execute `docker-compose up -d`
2. Enter container with `docker exec -ti points bash`
3. Execute `composer install`
4. Rename `.env.example` file into `.env`
5. Execute `./artisan migrate --force` to run database migrations
6. In `.env` file write your `IP_STACK_KEY` and `API_TOKEN`
7. Check api status by address `http://localhost:8080/api/v1/health?api_token=123`

## Tests 

To run unit tests execute `vendor/bin/phpunit tests/unit` inside `points` container.

To run only functional tests execute `vendor/bin/phpunit tests/functional`.

To run all tests execute `vendor/bin/phpunit`.

## Working with API

### Token auth

All requests must contain `api_token` parameter with `API_TOKEN` environment variable value.

Example: `GET /api/v1/health?api_token=123`

### Health check

Get health status of the service.

URL: `GET /api/v1/health`

[Description](docs/health.md) 

### Point related requests

Endpoints for viewing and manipulating points.

* [Add Point](docs/points/add.md): `POST /api/v1/points`
* [Update Point](docs/points/update.md): `PUT /api/v1/points/:pk`
* [Show points by desired ip and radius](docs/points/inrad.md): `GET /api/v1/points/inrad`
* [Show points in desired city](docs/points/in.md): `GET /api/v1/points/in/:city`