<h3 align="center">Slim 4 Skeleton</h3>

Based on (https://github.com/odan/slim4-skeleton)

## Requirements

* Make
* Docker

Inside docker container will start PHP 8.2 (FPM), mongodb, mysql, memcached.

## Installation

Install Make and Docker locally.
Copy config/env.sample.devel.php to config/env.php.
Run `make up` to start docker containers and application (will listen on localhost:8080).
Run `make test` to run tests (inside container).
Run `make` to see other commands (see Makefile).

## TODO
- json parser middleware
- Abstraction for MySql
- Abstraction for mongodb
- Cache implementation
- proper `make install` to copy env file and run migrations
- add tracy https://github.com/semhoun/runtracy
- data grid abstraction, 

## Features

This project is based on best practices and industry standards:

* [Standard PHP package skeleton](https://github.com/php-pds/skeleton)
* [Slim 4 Skeleton](https://github.com/odan/slim4-skeleton)
* HTTP router (Slim)
* HTTP message interfaces (PSR-7)
* HTTP Server Request Handlers, Middleware (PSR-15)
* Dependency injection container (PSR-11)
* Autoloader (PSR-4)
* Logger (PSR-3)
* Code styles (PSR-12)
* Single action controllers
* Unit- and integration tests
* Tested with [Github Actions](https://github.com/odan/slim4-skeleton/actions) and [Scrutinizer CI](https://scrutinizer-ci.com/)
* [PHPStan](https://github.com/phpstan/phpstan)
* PHP CS Fixer and PHPCS
