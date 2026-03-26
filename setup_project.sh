#!/bin/bash
set -e

echo "Purging Docker containers, volumes and images..."
docker compose down --volumes --rmi all 2>/dev/null || true

echo "Building Docker images..."
docker compose build

if [ ! -f .env ]; then
  echo "Creating .env from .env.example..."
  cp .env.example .env
fi

echo "Installing dependencies..."
docker compose run --rm app composer install --no-scripts

echo "Starting services..."
docker compose up -d

echo "Generating JWT keys..."
make jwt-keys

echo "Running migrations..."
make migrate

echo ""
echo "Setup complete! API available at http://localhost:8080"
