# PhotoMatch - Makefile
# Commandes simplifiées pour la gestion Docker

.PHONY: help install up down build rebuild logs shell artisan composer npm fresh migrate seed test clean

# Default target
help:
	@echo ""
	@echo "PhotoMatch - Commandes disponibles"
	@echo "=================================="
	@echo ""
	@echo "  make install    - Premier lancement (copie .env, build, up)"
	@echo "  make up         - Démarrer les containers"
	@echo "  make down       - Arrêter les containers"
	@echo "  make build      - Construire les images"
	@echo "  make rebuild    - Reconstruire sans cache"
	@echo "  make logs       - Voir les logs (tous les services)"
	@echo "  make logs-php   - Voir les logs PHP"
	@echo ""
	@echo "  make shell      - Shell bash dans le container PHP"
	@echo "  make artisan    - Exécuter une commande artisan"
	@echo "  make composer   - Exécuter une commande composer"
	@echo "  make npm        - Exécuter une commande npm"
	@echo ""
	@echo "  make fresh      - Reset complet (migrate:fresh + seed)"
	@echo "  make migrate    - Exécuter les migrations"
	@echo "  make seed       - Exécuter les seeders"
	@echo "  make test       - Lancer les tests"
	@echo ""
	@echo "  make clean      - Supprimer volumes et containers"
	@echo ""
	@echo "URLs:"
	@echo "  Application  : http://localhost:8080"
	@echo "  phpMyAdmin   : http://localhost:8081"
	@echo "  Mailpit      : http://localhost:8025"
	@echo "  Vite HMR     : http://localhost:5173"
	@echo ""

# Installation initiale
install:
	@if [ ! -f .env ]; then \
		echo "📄 Creating .env file..."; \
		cp .env.example .env; \
	fi
	@echo "🔨 Building Docker images..."
	docker compose build
	@echo "🚀 Starting containers..."
	docker compose up -d
	@echo ""
	@echo "✅ Installation complete!"
	@echo ""
	@echo "📝 Check logs with: make logs-php"
	@echo "🌐 Application will be available at: http://localhost:8080"

# Démarrer les containers
up:
	docker compose up -d
	@echo "✅ Containers started!"
	@echo "🌐 http://localhost:8080"

# Démarrer avec logs
up-logs:
	docker compose up

# Arrêter les containers
down:
	docker compose down
	@echo "✅ Containers stopped!"

# Construire les images
build:
	docker compose build

# Reconstruire sans cache
rebuild:
	docker compose build --no-cache
	docker compose up -d

# Voir les logs
logs:
	docker compose logs -f

logs-php:
	docker compose logs -f php

logs-nginx:
	docker compose logs -f nginx

logs-mysql:
	docker compose logs -f mysql

# Shell dans le container PHP
shell:
	docker compose exec php bash

# Commandes Artisan
artisan:
	docker compose exec php php artisan $(filter-out $@,$(MAKECMDGOALS))

# Commandes Composer
composer:
	docker compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

# Commandes NPM
npm:
	docker compose exec php npm $(filter-out $@,$(MAKECMDGOALS))

# Reset complet de la base de données
fresh:
	docker compose exec php php artisan migrate:fresh --seed
	@echo "✅ Database reset complete!"

# Migrations
migrate:
	docker compose exec php php artisan migrate

# Seeders
seed:
	docker compose exec php php artisan db:seed

# Tests
test:
	docker compose exec php php artisan test

# Nettoyage complet
clean:
	@echo "⚠️  This will remove all containers, volumes, and images for this project."
	@read -p "Are you sure? [y/N] " confirm && [ "$$confirm" = "y" ] || exit 1
	docker compose down -v --rmi local
	@echo "✅ Cleanup complete!"

# Status des containers
status:
	docker compose ps

# Restart des containers
restart:
	docker compose restart

# Permet d'utiliser des arguments avec make (ex: make artisan migrate:status)
%:
	@:
