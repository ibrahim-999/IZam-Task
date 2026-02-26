.PHONY: help up down restart build logs \
       install migrate seed fresh setup \
       test test-unit test-feature lint \
       routes swagger cache-clear optimize \
       shell db-shell redis-shell tinker \
       dump-autoload key-generate \
       rollback status

APP_CONTAINER = app

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# ─── Docker ───────────────────────────────────────────────────────────

up: ## Start all containers
	docker compose up -d

down: ## Stop all containers
	docker compose down

restart: ## Restart all containers
	docker compose restart

build: ## Rebuild all containers
	docker compose up -d --build

logs: ## Tail container logs
	docker compose logs -f

logs-app: ## Tail app container logs
	docker compose logs -f $(APP_CONTAINER)

# ─── Setup ────────────────────────────────────────────────────────────

install: ## Install composer dependencies
	docker compose exec $(APP_CONTAINER) composer install

setup: up install key-generate migrate seed swagger ## Full project setup (up, install, key, migrate, seed, swagger)

key-generate: ## Generate application key
	docker compose exec $(APP_CONTAINER) php artisan key:generate

# ─── Database ─────────────────────────────────────────────────────────

migrate: ## Run database migrations
	docker compose exec $(APP_CONTAINER) php artisan migrate

rollback: ## Rollback the last migration batch
	docker compose exec $(APP_CONTAINER) php artisan migrate:rollback

seed: ## Run database seeders
	docker compose exec $(APP_CONTAINER) php artisan db:seed

fresh: ## Drop all tables, re-migrate, and seed
	docker compose exec $(APP_CONTAINER) php artisan migrate:fresh --seed

status: ## Show migration status
	docker compose exec $(APP_CONTAINER) php artisan migrate:status

# ─── Testing ──────────────────────────────────────────────────────────

test: ## Run all tests
	docker compose exec $(APP_CONTAINER) php artisan test

test-unit: ## Run unit tests only
	docker compose exec $(APP_CONTAINER) php artisan test --testsuite=Unit

test-feature: ## Run feature tests only
	docker compose exec $(APP_CONTAINER) php artisan test --testsuite=Feature

test-filter: ## Run tests matching a filter (usage: make test-filter FILTER=test_name)
	docker compose exec $(APP_CONTAINER) php artisan test --filter=$(FILTER)

# ─── Code Quality ─────────────────────────────────────────────────────

lint: ## Run Laravel Pint code formatter
	docker compose exec $(APP_CONTAINER) ./vendor/bin/pint

lint-check: ## Check code style without fixing
	docker compose exec $(APP_CONTAINER) ./vendor/bin/pint --test

# ─── Artisan Utilities ────────────────────────────────────────────────

routes: ## List all registered routes
	docker compose exec $(APP_CONTAINER) php artisan route:list

swagger: ## Generate Swagger/OpenAPI documentation
	docker compose exec $(APP_CONTAINER) php artisan l5-swagger:generate

cache-clear: ## Clear all caches (config, route, view, app)
	docker compose exec $(APP_CONTAINER) php artisan config:clear
	docker compose exec $(APP_CONTAINER) php artisan route:clear
	docker compose exec $(APP_CONTAINER) php artisan view:clear
	docker compose exec $(APP_CONTAINER) php artisan cache:clear

optimize: ## Cache config, routes, and views for production
	docker compose exec $(APP_CONTAINER) php artisan config:cache
	docker compose exec $(APP_CONTAINER) php artisan route:cache
	docker compose exec $(APP_CONTAINER) php artisan view:cache

dump-autoload: ## Regenerate composer autoload files
	docker compose exec $(APP_CONTAINER) composer dump-autoload

# ─── Shell Access ─────────────────────────────────────────────────────

shell: ## Open a bash shell in the app container
	docker compose exec $(APP_CONTAINER) bash

tinker: ## Open Laravel Tinker REPL
	docker compose exec $(APP_CONTAINER) php artisan tinker

db-shell: ## Open MySQL shell
	docker compose exec db mysql -u izam -psecret izam_inventory

redis-shell: ## Open Redis CLI
	docker compose exec redis redis-cli
