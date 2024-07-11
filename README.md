<h3 align="center">Slim 4 Skeleton</h3>

Based on (https://github.com/odan/slim4-skeleton)

## Requirements

* Make
* Docker

Inside docker container will start PHP 8.2 (FPM), mongodb, mysql, memcached.

## Installation

- Install Make and Docker locally.
- Run `make up` to start docker containers and application (will listen on localhost:8080).
- Run `make test` to run tests (inside container).
- Run `make` to see other commands (see Makefile).

## DB

Using [doctrine orm](https://www.doctrine-project.org/projects/orm.html) with [migrations](https://www.doctrine-project.org/projects/migrations.html) support.

- `make enter` to connect to docker container.
- `./bin/doctrine` to run dedicated doctrine console commands.
  - Must be run within docker container because connection settings are using docker network host and also will make sure all env requirements are met.
- `./bin/doctrine migrations:diff` to generate migration.
  - new migrations will be located in `/resources/db/migrations`.
- `./bin/doctrine migrations:migrate --dry-run` to test migrations to be run, and without --dry-run to execute them
- see `/config/settings.php` for migration specific settings.
- See [Doctrine Migrations docs](https://www.doctrine-project.org/projects/doctrine-migrations/en/3.7/reference/managing-migrations.html) for extensive usage help.

## TODO

- fix/update scrutinizer config
- json parser middleware
- MongoDb integration
- Fix cache/memcache integration
- proper `make install` to copy env file and run migrations
- add tracy https://github.com/semhoun/runtracy
- add ecotone https://docs.ecotone.tech/
- data grid abstraction

## References

- [Standard PHP package skeleton](https://github.com/php-pds/skeleton)
- Based on [odan/slim4-skeleton](https://github.com/odan/slim4-skeleton)
- HTTP router (Slim)
- HTTP message interfaces (PSR-7)
- HTTP Server Request Handlers, Middleware (PSR-15)
- Dependency injection container (PSR-11)
- Autoloader (PSR-4)
- Logger (PSR-3)
- Code styles (PSR-12)
- [PHPStan](https://github.com/phpstan/phpstan)