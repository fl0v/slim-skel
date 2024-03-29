
help:
	@printf "\033[33mUsage:\033[0m\n  make [target] [arg=\"val\"...]\n\n\033[33mTargets:\033[0m\n"
	@grep -E '^[-a-zA-Z0-9_\.\/]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

up: ## start docker container and run app
	@docker-compose up -d && docker-compose exec app composer install

down: ## stop docker container
	@docker-compose down

logs: ## tail container logs
	@docker-compose logs -f -t	

enter: ## connect to container shell
	@docker-compose exec app sh

test: ## run unit tests
	@docker-compose exec app vendor/bin/phpunit --configuration phpunit.xml --do-not-cache-result --colors=always --display-deprecations

test-coverage: ## run unit tests with coverage
	@docker-compose exec app php -d xdebug.mode=coverage -r \"require 'vendor/bin/phpunit';\" -- --configuration phpunit.xml --do-not-cache-result --colors=always --coverage-clover runtime/logs/clover.xml --coverage-html runtime/coverage

check: ## run php-cs-fixer and phpcs checks in dry mode
	@PHP_CS_FIXER_IGNORE_ENV=1
	@docker-compose exec app vendor/bin/php-cs-fixer fix --dry-run --format=txt --verbose --diff --config=.cs.php --ansi
	@docker-compose exec app vendor/bin/phpcs --standard=phpcs.xml

fix: ## apply php-cs-fixer and phpcbf fixes
	@PHP_CS_FIXER_IGNORE_ENV=1
	@docker-compose exec app vendor/bin/php-cs-fixer fix --config=.cs.php --ansi --verbose
	@docker-compose exec app vendor/bin/phpcbf --standard=phpcs.xml

phpstan: ## run phpstan
	@docker-compose exec app vendor/bin/phpstan analyse -c phpstan.neon --no-progress --ansi

test-all: check phpstan test ## run php-cs-fixer, php-cs, phpstan Aand unit tests
