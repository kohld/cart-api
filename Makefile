.PHONY: setup up down composer-install composer-update migration migrate fixtures cs-fix cs-check jwt-keys test test-coverage analyse

setup:
	./setup_project.sh

up:
	docker compose up -d

down:
	docker compose down

composer-install:
	docker compose exec app composer install

composer-update:
	docker compose exec app composer update

migration:
	docker compose exec app bin/console doctrine:migrations:diff

migrate:
	docker compose exec app bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	docker compose exec app bin/console doctrine:fixtures:load --no-interaction

cs-fix:
	docker compose run --rm app vendor/bin/php-cs-fixer fix

cs-check:
	docker compose run --rm app vendor/bin/php-cs-fixer fix --dry-run --diff

jwt-keys:
	docker compose exec app bin/console lexik:jwt:generate-keypair --skip-if-exists

test:
	docker compose exec app bin/phpunit

test-coverage:
	docker compose exec app bin/phpunit --coverage-html var/coverage

analyse:
	docker compose exec app vendor/bin/phpstan analyse --no-progress
