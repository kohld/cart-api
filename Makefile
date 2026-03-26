.PHONY: setup up down migrate fixtures cs-fix cs-check jwt-keys

setup:
	./setup_project.sh

up:
	docker compose up -d

down:
	docker compose down

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
