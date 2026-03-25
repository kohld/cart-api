#!/bin/bash
set -e

echo "Purging Docker containers, volumes and images..."
docker compose down --volumes --rmi all 2>/dev/null || true

echo "Building Docker images..."
docker compose build

echo "Installing dependencies..."
docker compose run --rm app composer install

echo "Starting services..."
docker compose up -d

echo ""
echo "Setup complete! API available at http://localhost:8080"
