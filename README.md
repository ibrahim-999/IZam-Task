# Izam Inventory Management System

A containerized inventory management platform with a Laravel REST API backend.

## Prerequisites

- Docker & Docker Compose

## Quick Start

```bash
git clone <repo-url> izam && cd izam
cp .env.example .env
make setup
```

This runs containers, installs dependencies, generates the app key, runs migrations, seeds demo data, and generates Swagger docs.

The API is available at **http://localhost:8020/api/v1**

Swagger UI is available at **http://localhost:8020/api/documentation**

## Demo Credentials

| Email               | Password   |
|---------------------|------------|
| `test@example.com`  | `password` |

## Project Structure

```
izam/
├── docker/
│   ├── mysql/          # MySQL config
│   ├── nginx/          # Nginx config
│   └── php/            # PHP-FPM Dockerfile
├── laravel/            # Laravel application
├── frontend/           # Frontend (placeholder)
├── docker-compose.yml
└── Makefile
```

See [laravel/README.md](laravel/README.md) for API details, architecture, and endpoint documentation.

## Make Commands

Run `make help` to see all commands. Key ones:

| Command            | Description                                      |
|--------------------|--------------------------------------------------|
| `make setup`       | Full project setup (containers, deps, DB, docs)  |
| `make up`          | Start containers                                 |
| `make down`        | Stop containers                                  |
| `make test`        | Run all tests                                    |
| `make fresh`       | Drop DB, re-migrate, and seed                    |
| `make swagger`     | Regenerate Swagger docs                          |
| `make lint`        | Run Pint code formatter                          |
| `make routes`      | List all API routes                              |
| `make shell`       | Bash into the app container                      |
| `make logs`        | Tail container logs                              |
| `make cache-clear` | Clear all application caches                     |

## Services

| Service | Container    | Host Port |
|---------|-------------|-----------|
| Nginx   | izam-nginx  | 8020      |
| MySQL   | izam-db     | 3320      |
| Redis   | izam-redis  | 6390      |
| PHP-FPM | izam-app    | (internal)|
