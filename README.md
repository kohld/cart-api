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
  - [Cart with User association](#cart-with-user-association)
  - [PHP 8.4 property hooks](#php-84-property-hooks)
  - [No checkout endpoint](#no-checkout-endpoint)
  - [Cart URL design: `/me` vs. explicit cart UUID](#cart-url-design-me-vs-explicit-cart-uuid)
  - [PATCH instead of PUT](#patch-instead-of-put)
- [Learnings](#learnings)
  - [JWT (Symfony Integration with LexikJWT)](#jwt-symfony-integration-with-lexikjwt)
  - [MapRequestPayload](#maprequestpayload)

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
- **Insomnia Export:** a ready-to-import collection is available at `docs/insomnia.yaml` for local testing

### Endpoints

<details>
<summary>GET /api/v1/health</summary>

Public. Returns API and database status.

```json
// 200
{ "status": "ok", "database": "ok" }

// 503
{ "status": "error", "database": "unavailable" }
```

</details>

<details>
<summary>POST /api/v1/auth/register</summary>

Public. Creates a new user and an associated cart.

```json
// Request
{
    "email": "user@example.com",
    "plainPassword": "yourpassword"
}

// 201
{
    "id": "uuid",
    "email": "user@example.com"
}
```

</details>

<details>
<summary>POST /api/v1/auth/login</summary>

Public. Returns a JWT token.

```json
// Request
{
    "email": "user@example.com",
    "password": "yourpassword"
}

// 200
{
    "token": "eyJ..."
}
```

</details>

<details>
<summary>GET /api/v1/carts/me</summary>

Requires authentication. Returns the cart of the authenticated user.

```
Authorization: Bearer <token>
```

```json
// 200
{
    "id": "uuid",
    "items": [
        {
            "id": "uuid",
            "product": {
                "id": "uuid",
                "name": "iPhone 14 Pro (refurbished)",
                "articleNumber": "APL-IP14P-256-SG"
            },
            "quantity": 2,
            "price": "649.99"
        }
    ],
    "total": "1299.98"
}
```

</details>

<details>
<summary>POST /api/v1/carts/me/items</summary>

Requires authentication. Adds a product to the cart. If the product is already in the cart, the quantity is increased by the given amount.

```
Authorization: Bearer <token>
```

```json
// Request
{
    "productId": "uuid",
    "quantity": 1
}

// 201 - returns updated cart (same as GET /api/v1/carts/me)
```

</details>

<details>
<summary>PATCH /api/v1/carts/me/items/{id}</summary>

Requires authentication. Updates the quantity of a cart item.

```
Authorization: Bearer <token>
```

```json
// Request
{
    "quantity": 3
}

// 200 - returns updated cart (same as GET /api/v1/carts/me)
```

</details>

<details>
<summary>DELETE /api/v1/carts/me/items/{id}</summary>

Requires authentication. Removes an item from the cart.

```
Authorization: Bearer <token>
```

```
// 204 No Content
```

</details>

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
| `make test` | Run the test suite |
| `make test-coverage` | Run tests and generate HTML coverage report in `var/coverage` |
| `make analyse` | Run PHPStan static analysis |

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

### Cart with User association

Without a user association, the cart is identified only by its UUID.  
The client receives the UUID on creation and sends it with every request in the URL.

**No longer required:**
- User Entity
- JWT Auth
- Ownership check

**But:** Anyone who knows the UUID can view and manipulate the cart. This means no protection for buyers.

### PHP 8.4 property hooks

PHP 8.4 introduced property hooks, allowing `get` and `set` logic directly on class properties without separate
getter/setter methods. Doctrine and Symfony do not yet fully support them.

Classical getters and setters were used in this project to avoid potential compatibility issues.

### No checkout endpoint

A checkout endpoint was intentionally left out.  
This project has no payment/discount logic, no order process and no Order entity.

Checkout goes beyond the requirements of this proof of concept.

### Cart URL design: `/me` vs. explicit cart UUID

Two approaches were considered for the cart endpoints:

**Option A: `/api/v1/carts/{cartId|cartUuid}/items`**  
Strictly RESTful, where the cart is a named resource with its own UUID. Which works well if a user can have multiple
carts.

**Option B: `/api/v1/carts/me/items`**  
Uses the `/me` convention, widely adopted by APIs like Spotify and GitHub for "the current user's resource".  
Self-documenting and removes the need for the client to manage a cart UUID.

Since each user has exactly one cart, tied to their JWT token, exposing a cart UUID in the URL adds no value and forces
the client to first fetch the cart UUID before making any item requests.

`/me` was chosen as the cleaner and more practical approach for this 1:1 relationship of User and Cart entities.

### PATCH instead of PUT

`PATCH /api/v1/carts/me/items/{id}` only updates the `quantity` field.  
The complete resource is not sent or replaced.

`PUT` implies a full resource replacement and would require all fields to be included in the request. `PATCH` is the semantically correct choice for partial updates.

---

## Learnings

### JWT (Symfony Integration with LexikJWT)

The generated token is sent with every subsequent request via the `Authorization: Bearer` header and validated 
automatically by the JWT firewall.

### MapRequestPayload

Introduced in Symfony 6.3, this attribute replaces manual request DTO deserialization and validation in one step.
