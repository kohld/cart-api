#!/bin/bash
set -e

echo "Purging Docker containers, volumes and images..."
docker compose down --volumes --rmi all 2>/dev/null || true

echo "Purging Symfony files..."
rm -rf vendor composer.json composer.lock symfony.lock \
  src config bin public var migrations tests \
  phpunit.dist.xml compose.override.yaml .env

echo "Building Docker images..."
docker compose build

echo "Creating Symfony project..."
docker compose run --rm app sh -c "
  composer create-project symfony/skeleton /tmp/symfony &&
  cp -r /tmp/symfony/bin /var/www/html/bin &&
  cp -r /tmp/symfony/config /var/www/html/config &&
  cp -r /tmp/symfony/public /var/www/html/public &&
  cp -r /tmp/symfony/src /var/www/html/src &&
  cp /tmp/symfony/composer.json /var/www/html/composer.json &&
  cp /tmp/symfony/composer.lock /var/www/html/composer.lock &&
  cp /tmp/symfony/symfony.lock /var/www/html/symfony.lock &&
  cp /tmp/symfony/.env /var/www/html/.env
"

echo "Installing packages..."
docker compose run --rm -e SYMFONY_DOCKER=false app composer require \
  symfony/orm-pack \
  symfony/validator \
  symfony/serializer-pack \
  symfony/security-bundle \
  lexik/jwt-authentication-bundle

echo "Installing dev packages..."
docker compose run --rm -e SYMFONY_DOCKER=false app composer require --dev \
  phpunit/phpunit \
  symfony/test-pack

echo "Removing auto-generated Doctrine services from docker-compose.yml..."
sed -i '' '/^###> doctrine\/doctrine-bundle ###/,/^###< doctrine\/doctrine-bundle ###/d' docker-compose.yml

echo "Starting services..."
docker compose up -d

echo ""
echo "Setup complete! API available at http://localhost:8080"
