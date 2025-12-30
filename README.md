# ToyShop

Welcome! This is a simple e-commerce demo application for a toy store, built with Laravel 12, Vue 3, and Inertia.js.

## Getting Started

Starting the project is straightforward. Just run:

```bash
docker compose up -d
```

The containers will automatically install all dependencies (Composer and npm) on first launch. Give it a minute or two.

Once everything is up, run the database migrations to set up tables and seed demo data:

```bash
docker compose exec php php artisan migrate --seed
```

That's it! You're ready to go.

## Where to Find Everything

- **Shop frontend:** http://localhost:8080
- **Admin dashboard:** http://localhost:8080/dashboard
- **Email inbox (Mailpit):** http://localhost:8025

## Login Credentials

Two test accounts are created for you:

**Admin account:**
- Email: `admin@test.com`
- Password: `password`

**Regular customer:**
- Email: `user@test.com`
- Password: `password`

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.4 |
| Frontend | Vue 3, Inertia.js v2, TypeScript |
| Styling | Tailwind CSS v4, shadcn-vue |
| Build | Vite, Node.js |
| Database | MySQL 8 |
| Queue/Cache | Redis |
| Email | Mailpit (dev) |

## What's Inside

The application includes:
- Product catalog with 10 toy products (with images)
- Shopping cart for authenticated users
- Checkout flow that creates orders
- Order history for customers
- Admin dashboard with sales statistics and order management
- Product management (create, edit, delete with image upload)

## Email Notifications

All emails are sent through a queue and can be viewed at http://localhost:8025 (Mailpit).

| Email | Recipient | Trigger |
|-------|-----------|---------|
| Order Confirmation | Customer | After successful checkout |
| New Order Notification | Admin | After successful checkout |
| Low Stock Alert | Admin | Scheduled every 30 minutes (products with 1-5 units) |
| Daily Sales Report | Admin | Scheduled daily at 18:00 |

## Scheduled Tasks

| Command | Schedule | Description |
|---------|----------|-------------|
| `stock:check-low` | Every 30 minutes | Checks products with stock 1-5 units and sends one email with all low stock items |
| `sales:daily-report` | Daily at 18:00 | Sends sales summary for the day |

## Testing Email Features Manually

```bash
# Low Stock Alert
docker compose exec php php artisan stock:check-low

# Daily Sales Report
docker compose exec php php artisan sales:daily-report
```

## About the Containers

| Container | What it does |
|-----------|--------------|
| `local_php` | Runs the Laravel app with PHP 8.4 + Node.js (for Vite/Vue builds) |
| `local_nginx` | Web server on port 8080 |
| `local_mysql` | Database on port 3306 |
| `local_redis` | Powers queues and caching |
| `local_worker` | Processes background jobs and scheduled tasks |
| `local_mailpit` | Catches all emails for preview |

### Why is Node.js inside the PHP container?

This is a common setup for Laravel + Inertia.js + Vue applications:

1. **Vite integration** - Laravel uses Vite for frontend bundling. Having Node.js in the same container simplifies running `npm run dev` for hot-reload during development and `npm run build` for production builds.

2. **Simplified workflow** - No need to manage a separate Node container. All commands (`composer`, `npm`, `artisan`) run from the same place.

3. **Automatic dependency installation** - On container startup, both `composer install` and `npm install` run automatically, so you're ready to go immediately.

## Development Commands

```bash
# Rebuild frontend assets
docker compose exec php npm run build

# Watch for changes during development
docker compose exec php npm run dev

# Run Laravel Pint (code formatter)
docker compose exec php vendor/bin/pint

# Clear all caches
docker compose exec php php artisan optimize:clear

# View logs
docker compose logs -f php
docker compose logs -f worker
```

## Running Tests

```bash
docker compose exec php php artisan test
```

## Need to Start Fresh?

Reset the database and reseed everything:

```bash
docker compose exec php php artisan migrate:fresh --seed
```

---

Happy reviewing!
