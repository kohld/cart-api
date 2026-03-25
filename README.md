# Cart API

A RESTful shopping cart API built with Symfony 8 and PostgreSQL. Supports JWT authentication, product management and cart operations.

## Table of Contents

- [Requirements](#requirements)
- [Setup](#setup)
- [Usage](#usage)
- [Development](#development)
  - [Code Style](#code-style)
  - [Fixtures](#fixtures)

---

## Requirements

- Docker
- Docker Compose

## Setup

Run once to initialize the project, or again to restart a clean Docker environment. Docker containers, volumes and images will be purged — project files are preserved.

```bash
./setup_project.sh
```

> During setup, a prompt may appear: Docker configuration from recipes — enter `x` to decline permanently (we manage our own Docker setup).

## Usage

**Start:**
```bash
docker compose up -d
```

The API is available at: http://localhost:8080

**Stop:**
```bash
docker compose down
```

## Development

### Code Style

Code style is enforced via [PHP CS Fixer](https://cs.symfony.com) using the Symfony ruleset (a superset of PSR-12). A GitHub Action runs on every push and pull request.

Check for violations:
```bash
docker compose run --rm app vendor/bin/php-cs-fixer fix --dry-run --diff
```

Fix violations automatically:
```bash
docker compose run --rm app vendor/bin/php-cs-fixer fix
```

### Fixtures

Load sample refurbished hardware products into the database:

```bash
docker compose run --rm app php bin/console doctrine:fixtures:load --no-interaction
```

> Warning: This will purge all existing data before loading the fixtures.
