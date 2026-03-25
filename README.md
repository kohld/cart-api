# Cart API

## Setup

Run to initialize the project or restart a clean Docker environment. Docker containers, volumes and images will be purged. Project files are preserved.

```bash
./setup_project.sh
```


## Start

```bash
docker compose up -d
```

The API is available at: http://localhost:8080

## Stop

```bash
docker compose down
```

## Code Style

Check for code style violations:

```bash
docker compose run --rm app vendor/bin/php-cs-fixer fix --dry-run --diff
```

Fix code style violations automatically:

```bash
docker compose run --rm app vendor/bin/php-cs-fixer fix
```

## Fixtures

Load sample products into the database:

```bash
docker compose run --rm app php bin/console doctrine:fixtures:load --no-interaction
```

> Warning: This will purge all existing data before loading the fixtures.
