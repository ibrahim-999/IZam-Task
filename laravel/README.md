# Izam Inventory Management API

A RESTful API for managing inventory across multiple warehouses, built with Laravel 12, Sanctum authentication, and Domain-Driven Design.

## Tech Stack

- **PHP 8.3** / **Laravel 12**
- **MySQL 8.0** — relational database
- **Redis** — caching (warehouse inventory)
- **Laravel Sanctum** — API token authentication
- **L5-Swagger** — OpenAPI documentation
- **Docker Compose** — containerized environment (PHP-FPM, Nginx, MySQL, Redis)

## Architecture

### Domain-Driven Design

The application is organized into bounded contexts under `app/Domains/`:

```
app/
├── Domains/
│   ├── Auth/
│   │   ├── Http/
│   │   │   ├── Controllers/AuthController.php
│   │   │   ├── Requests/{LoginRequest, RegisterRequest}
│   │   │   └── Resources/UserResource.php
│   │   └── Services/AuthService.php
│   ├── Inventory/
│   │   ├── DTOs/InventoryItemData.php
│   │   ├── Http/
│   │   │   ├── Controllers/InventoryItemController.php
│   │   │   ├── Requests/{Store, Update}InventoryItemRequest
│   │   │   └── Resources/InventoryItemResource.php
│   │   ├── Models/InventoryItem.php
│   │   └── Services/InventoryService.php
│   ├── Warehouse/
│   │   ├── Events/{LowStockDetected, StockTransferred}
│   │   ├── Http/
│   │   │   ├── Controllers/WarehouseController.php
│   │   │   ├── Requests/{Store, Update}WarehouseRequest
│   │   │   └── Resources/{WarehouseResource, StockResource}
│   │   ├── Listeners/{SendLowStockNotification, InvalidateWarehouseCache}
│   │   ├── Models/{Warehouse, Stock}
│   │   ├── Notifications/LowStockNotification.php
│   │   └── Services/WarehouseService.php
│   └── Transfer/
│       ├── DTOs/StockTransferData.php
│       ├── Exceptions/InsufficientStockException.php
│       ├── Http/
│       │   ├── Controllers/StockTransferController.php
│       │   ├── Requests/StoreStockTransferRequest.php
│       │   └── Resources/StockTransferResource.php
│       ├── Models/StockTransfer.php
│       └── Services/StockTransferService.php
├── Documentations/V1/          # Swagger/OpenAPI annotations
├── Http/Controllers/Controller.php
├── Models/User.php
└── Providers/AppServiceProvider.php
```

### Key Patterns

- **Service Layer** — business logic in domain services, thin controllers
- **DTOs** — data transfer objects for structured input
- **Atomic Stock Transfers** — `DB::transaction` + `lockForUpdate` for data integrity
- **Event-Driven** — `LowStockDetected` fires when stock drops to 10 or below and sends an email notification to admin; `StockTransferred` invalidates warehouse cache
- **Redis Caching** — warehouse inventory cached with version-based invalidation on transfers
- **Email Notifications** — Low stock events trigger a `LowStockNotification` email to the admin via Laravel's Notification system

## Setup

### Prerequisites

- Docker & Docker Compose

### Installation

From the project root (where `docker-compose.yml` lives):

```bash
make setup
```

Or manually:

```bash
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan l5-swagger:generate
```

### Demo Credentials

| Email               | Password   |
|---------------------|------------|
| `test@example.com`  | `password` |

## API Documentation

Swagger UI is available at **http://localhost:8020/api/documentation** after running `make swagger`.

## API Endpoints

All endpoints are prefixed with `/api/v1`. Protected endpoints require a Bearer token.

### Authentication

| Method | Endpoint             | Auth | Description                  |
|--------|----------------------|------|------------------------------|
| POST   | `/api/v1/register`   | No   | Register a new user          |
| POST   | `/api/v1/login`      | No   | Login and receive API token  |
| POST   | `/api/v1/logout`     | Yes  | Revoke current token         |
| GET    | `/api/v1/me`         | Yes  | Get authenticated user       |

**Register request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Login request:**
```json
{
  "email": "test@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "message": "Logged in successfully.",
  "user": { "id": 1, "name": "Test User", "email": "test@example.com", "..." : "..." },
  "token": "1|abc123..."
}
```

Use the token in subsequent requests:
```
Authorization: Bearer 1|abc123...
```

### Inventory Items

| Method | Endpoint                  | Auth | Description                              |
|--------|---------------------------|------|------------------------------------------|
| GET    | `/api/v1/inventory`       | Yes  | List inventory per warehouse (paginated) |
| POST   | `/api/v1/inventory`       | Yes  | Create item                              |
| GET    | `/api/v1/inventory/{id}`  | Yes  | Show item                                |
| PUT    | `/api/v1/inventory/{id}`  | Yes  | Update item                              |
| DELETE | `/api/v1/inventory/{id}`  | Yes  | Delete item                              |

`GET /api/v1/inventory` returns stock records (inventory per warehouse), each including warehouse and inventory item details.

**Filters** (query parameters on `GET /api/v1/inventory`):
- `warehouse_id` — filter by warehouse
- `name` — partial match on item name
- `category` — exact match
- `price_min` / `price_max` — price range
- `per_page` — items per page (default: 15)

**Create/Update body:**
```json
{
  "name": "Wireless Mouse",
  "sku": "WM-001",
  "description": "Ergonomic wireless mouse",
  "price": 29.99,
  "category": "Electronics"
}
```

### Warehouses

| Method | Endpoint                               | Auth | Description              |
|--------|----------------------------------------|------|--------------------------|
| GET    | `/api/v1/warehouses`                   | Yes  | List warehouses          |
| POST   | `/api/v1/warehouses`                   | Yes  | Create warehouse         |
| GET    | `/api/v1/warehouses/{id}`              | Yes  | Show warehouse           |
| PUT    | `/api/v1/warehouses/{id}`              | Yes  | Update warehouse         |
| DELETE | `/api/v1/warehouses/{id}`              | Yes  | Delete warehouse         |
| GET    | `/api/v1/warehouses/{id}/inventory`    | Yes  | Get warehouse inventory (paginated) |

**Create/Update body:**
```json
{
  "name": "Main Warehouse",
  "location": "Cairo, Egypt"
}
```

### Stock Transfers

| Method | Endpoint                        | Auth | Description            |
|--------|---------------------------------|------|------------------------|
| GET    | `/api/v1/stock-transfers`       | Yes  | List transfers         |
| POST   | `/api/v1/stock-transfers`       | Yes  | Create transfer        |
| GET    | `/api/v1/stock-transfers/{id}`  | Yes  | Show transfer details  |

**Create transfer body:**
```json
{
  "from_warehouse_id": 1,
  "to_warehouse_id": 2,
  "inventory_item_id": 1,
  "quantity": 20
}
```

**Transfer logic:**
- Source warehouse stock is locked and validated (must have sufficient quantity)
- Source decremented, destination incremented (created if no stock record exists)
- Audit record created in `stock_transfers` table
- If source stock drops to **10 or below**, `LowStockDetected` event fires and an email notification is sent to the admin
- Cache for both warehouses is invalidated via `StockTransferred` event

## Postman Collection

A ready-to-use Postman collection is available in the `postman/` directory with **64 requests** covering all API endpoints.

### Import

1. Open Postman and click **Import**.
2. Select both files from `postman/`:
   - `Izam_Inventory_API.postman_collection.json` — the collection
   - `Izam_Inventory_API.postman_environment.json` — the environment
3. Select the **Izam Inventory API - Local** environment in the top-right dropdown.
4. Run **Auth > Login - Success** first to auto-set the `{{token}}` variable.

### Coverage

| Folder | Requests | Scenarios |
|--------|----------|-----------|
| Auth | 14 | Register, login, logout, me — success, validation errors, unauthenticated |
| Inventory Items | 20 | CRUD — success, filters (name, category, price, warehouse), pagination, validation errors, not found, unauthenticated |
| Warehouses | 17 | CRUD + paginated inventory — success, custom pagination, validation errors, not found, unauthenticated |
| Stock Transfers | 13 | List, create, show — success, insufficient stock, same warehouse, invalid IDs, unauthenticated |

Every request includes test scripts that validate status codes and response structure. Environment variables (`token`, `inventory_item_id`, `warehouse_id`, etc.) are auto-set by test scripts on successful create/login responses.

## Testing

```bash
make test
```

| Suite   | Test                                  | Covers                               |
|---------|---------------------------------------|--------------------------------------|
| Unit    | `StockTransferServiceTest`            | Rejects over-transfers, zero stock   |
| Feature | `StockTransferTest`                   | API transfer flow, auth, validation  |
| Feature | `LowStockEventTest`                  | Event dispatch at threshold          |

## Database Schema

```
users              — id, name, email, password, timestamps
warehouses         — id, name, location, timestamps
inventory_items    — id, name, sku (unique), description, price, category, timestamps
stocks             — id, warehouse_id (FK), inventory_item_id (FK), quantity, timestamps
                     unique(warehouse_id, inventory_item_id)
stock_transfers    — id, from_warehouse_id (FK), to_warehouse_id (FK),
                     inventory_item_id (FK), user_id (FK), quantity, timestamps
```
