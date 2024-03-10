enter:
	@docker-compose exec app sh

up:
	@docker-compose up -d && docker-compose exec app composer install

down:
	@docker-compose down

.PHONY: tests
tests:
	@docker-compose exec app vendor/bin/phpunit tests