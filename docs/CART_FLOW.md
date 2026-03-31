# Cart Flow

This document describes the full process for interacting with the cart API.

From user registration to managing items in the cart.

## Table of Contents

- [Overview](#overview)
- [Step 1: Register a User](#step-1-register-a-user)
- [Step 2: Login](#step-2-login)
- [Step 3: Use the Cart](#step-3-use-the-cart)
  - [View the cart](#view-the-cart)
  - [Add an item](#add-an-item)
  - [Update item quantity](#update-item-quantity)
  - [Remove an item](#remove-an-item)
- [Error Responses](#error-responses)
- [Testing Locally](#testing-locally)

---

## Overview

```
1. Register  →  POST /api/v1/auth/register
2. Login     →  POST /api/v1/auth/login        (returns JWT)
3. Cart      →  GET/POST/PATCH/DELETE /api/v1/carts/me/**
```

Authentication is required for all cart endpoints. The JWT token from the login step must be included in every cart
request as a Bearer token.

---

## Step 1: Register a User

Creates a new user account. An empty cart is automatically created alongside it.

**Request**

```http
POST /api/v1/auth/register
Content-Type: application/json

{
    "email": "user@example.com",
    "plainPassword": "yourpassword"
}
```

**Response** `201 Created`

```json
{
    "id": "019703d3-5b5b-7000-8000-000000000001",
    "email": "user@example.com",
    "_links": {
        "login": { "href": "/api/v1/auth/login", "method": "POST" },
        "cart":  { "href": "/api/v1/carts/me",   "method": "GET"  }
    }
}
```

---

## Step 2: Login

Authenticates the user and returns a JWT token. This token is required for all subsequent cart requests.

**Request**

```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "yourpassword"
}
```

**Response** `200 OK`

```json
{
    "token": "eyJhbGciOiJSUzI1NiJ9..."
}
```

> Store the token (_valid for 4 hours_) it must be sent as `Authorization: Bearer <token>` with every cart request.

---

## Step 3: Use the Cart

All cart endpoints require the JWT token from Step 2.

```
Authorization: Bearer eyJhbGciOiJSUzI1NiJ9...
```

### View the cart

```http
GET /api/v1/carts/me
```

**Response** `200 OK`

```json
{
    "id": "019703d3-5b5b-7000-8000-000000000002",
    "items": [],
    "total": "0.00",
    "_links": {
        "self":    { "href": "/api/v1/carts/me",       "method": "GET"  },
        "addItem": { "href": "/api/v1/carts/me/items", "method": "POST" }
    }
}
```

### Add an item

```http
POST /api/v1/carts/me/items
Content-Type: application/json

{
    "productId": "019703d3-5b5b-7000-8000-000000000010",
    "quantity": 2
}
```

**Response** `201 Created`:  returns the full updated cart.

> Add a product that's already in the cart, we'll simply increase the quantity instead of creating a duplicate entry.

### Update item quantity

```http
PATCH /api/v1/carts/me/items/{cartItemId}
Content-Type: application/json

{
    "quantity": 5
}
```

**Response** `200 OK`:  returns the full updated cart.

### Remove an item

```http
DELETE /api/v1/carts/me/items/{cartItemId}
```

**Response** `204 No Content`

---

## Error Responses

All errors follow a uniform structure:

```json
{
    "error": "Unprocessable Content",
    "violations": [
        { "field": "quantity", "message": "This value should not be blank." }
    ]
}
```

---

## Testing Locally

A ready-to-import Insomnia collection covering all endpoints is available at
[`docs/insomnia.yaml`](insomnia.yaml).
