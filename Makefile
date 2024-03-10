.RECIPEPREFIX +=
.DEFAULT_GOAL := help
.PHONY: *

help:
	@printf "\033[33mUsage:\033[0m\n  make [target] [arg=\"val\"...]\n\n\033[33mTargets:\033[0m\n"
	@grep -E '^[-a-zA-Z0-9_\.\/]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

start: ## start local php server
	php -S localhost:8080 -t public/

test-all: php-cs-fixer-check phpcs-check phpstan test ## run all checks (dry mode)

test: ## run phpunit tests
	@vendor/bin/phpunit --configuration phpunit.xml --do-not-cache-result --colors=always --display-deprecations

php-cs-fixer-check: ## run php-cs-fixer in dry mode
	@PHP_CS_FIXER_IGNORE_ENV=1
	@vendor/bin/php-cs-fixer fix --dry-run --format=txt --verbose --diff --config=.cs.php --ansi

php-cs-fixer-fix: ## run php-cs-fixer and apply changes
	@PHP_CS_FIXER_IGNORE_ENV=1
	@vendor/bin/php-cs-fixer fix --config=.cs.php --ansi --verbose

phpcs-check: ## run phpcs sniffer in dry mode
	@vendor/bin/phpcs --standard=phpcs.xml

phpcs-fix: ## run phpcs sniffer and apply changes
	@vendor/bin/phpcbf --standard=phpcs.xml

phpstan: ## run phpstan
	@vendor/bin/phpstan analyse -c phpstan.neon --no-progress --ansi

test-coverage: ## run unit tests with coverage
	@php -d xdebug.mode=coverage -r \"require 'vendor/bin/phpunit';\" -- --configuration phpunit.xml --do-not-cache-result --colors=always --coverage-clover runtime/logs/clover.xml --coverage-html runtime/coverage

docker-up: ## start docker container and run composer install
	@docker-compose up -d && docker-compose exec app composer install

docker-down: ## stop docker container
	@docker-compose down

docker-enter: ## connect to docker container
	@docker-compose exec app sh

docker-test: ## run unit tests in docker container
	@docker-compose exec app vendor/bin/phpunit tests
