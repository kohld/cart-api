# Cart API

A RESTful shopping cart API built with Symfony 8 and PostgreSQL.

Each registered user gets a dedicated cart, similar to how Amazon handles shopping carts. There is no guest or anonymous cart functionality: a user account is required to interact with the cart. Authentication is handled via JWT.

## Table of Contents

- [Requirements](#requirements)
- [Setup](#setup)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Development](#development)
  - [Code Style](#code-style)
  - [Fixtures](#fixtures)
  - [Makefile](#makefile)
- [Decisions](#decisions)
  - [Cart without User association](#cart-without-user-association)

---

## Requirements

- Docker
- Docker Compose

## Setup

Run once to initialize the project, or again to restart a clean Docker environment. Docker containers, volumes and images will be purged. Project files are preserved.

```bash
./setup_project.sh
```

> During setup, a prompt may appear: **Docker configuration from recipes**.
> 
> Enter `x` to decline permanently (we manage our own Docker setup).

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

## API Documentation

Swagger/OpenAPI was skipped for this project. Instead, the API is documented via README and an Insomnia export.

API documentation is provided in two forms:

- **README:** all endpoints are documented below with request and response examples
- **Insomnia Export:** a ready-to-import collection is available at `docs/insomnia.json` for local testing

### Endpoints

> Full endpoint documentation will be added as the API is implemented.

---

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

Code style is enforced via [PHP CS Fixer](https://cs.symfony.com) using the Symfony ruleset (a superset of PSR-12).  
A GitHub Action runs on every push and pull request.

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

---

## Decisions

### Cart without User association

Without a user association, the cart is identified only by its UUID.  
The client receives the UUID on creation and sends it with every request in the URL.

**No longer required:**
- User Entity
- JWT Auth
- Ownership check

**But:** Anyone who knows the UUID can view and manipulate the cart. This means no protection for buyers.
