# Cart API

A RESTful shopping cart API built with Symfony 8 and PostgreSQL. Supports JWT authentication, product management and cart operations.

## Table of Contents

- [Requirements](#requirements)
- [Setup](#setup)
- [Usage](#usage)
- [Development](#development)
  - [Code Style](#code-style)
  - [Fixtures](#fixtures)
  - [Makefile](#makefile)

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
make up
```

The API is available at: http://localhost:8080

**Stop:**
```bash
make down
```

## Development

### Makefile

All common commands are available via `make`:

| Command | Description |
|---|---|
| `make setup` | Purge Docker and reinitialize the project |
| `make up` | Start all containers |
| `make down` | Stop all containers |
| `make migrate` | Run database migrations |
| `make fixtures` | Load sample data (purges existing data) |
| `make cs-fix` | Fix code style violations automatically |
| `make cs-check` | Check for code style violations |

### Code Style

Code style is enforced via [PHP CS Fixer](https://cs.symfony.com) using the Symfony ruleset (a superset of PSR-12). A GitHub Action runs on every push and pull request.

```bash
make cs-check
make cs-fix
```

### Fixtures

Load sample refurbished hardware products into the database:

```bash
make fixtures
```

> Warning: This will purge all existing data before loading the fixtures.
