## Quick Usage Note

A small helper has been added to clean unnecessary tables in the database (truncate/delete unwanted ones) and run seeders.

## How to Run Seeders

The following commands can be used to run a single seeder file in a Laravel project.

-   Simple usage (with seeder class name):

```bash
php artisan db:seed --class=CategorySeeder
```

-   If full namespace is required (especially for Laravel 8+ or if using different namespace):

```bash
php artisan db:seed --class="Database\\Seeders\\CategorySeeder"
```

-   If there's a seeder call inside `DatabaseSeeder`, to run all `DatabaseSeeder`:

```bash
php artisan db:seed
```

Notes:

-   Make sure your database connection is properly configured in the `.env` file before running commands.
-   If you want to run migrations first:

```bash
php artisan migrate
```

-   If you're using SQLite in local development environment, make sure `DB_CONNECTION=sqlite` and `DB_DATABASE` settings are correct.

---

## Clean Database and Run Seeders (seed_clean)

The `scripts/seed_clean.php` helper script added to the project cleans remaining tables while preserving the specified tables and then runs seeders.

Default behavior:

-   Protected tables: `migrations`, `categories`
-   Seeder to run: `Database\\Seeders\\DatabaseSeeder`

Usage examples:

```bash
php scripts/seed_clean.php
```

or via composer:

```bash
composer seed:clean
```

Customization (tables to keep and seed classes):

```bash
php scripts/seed_clean.php --keep=categories,users --seeders=Database\\\\Seeders\\\\CategorySeeder,Database\\\\Seeders\\\\UserSeeder
```

Notes:

-   This script deletes data by truncating/deleting tables; use only in development environment or when backup is taken.
-   Compatible with PgSQL, MySQL and SQLite.

---

## Quick Setup (short and clear)

1. Prepare the repository and install dependencies:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

2. If using SQLite, create empty file and run migration:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --force
```

3. Create storage link (public/storage -> storage/app/public):

```bash
php artisan storage:link --force
```

4. Run seeders (example):

```bash
php artisan db:seed
```

5. Created sample admin accounts (added by seeder):

-   Email: admin@admin.com / Password: password (super_admin)
-   Email: omega@admin.com / Password: omega456 (normal admin)
-   Email: normaladmin@dut.com / Password: main123 (normal admin)

6. Development server:

**Important**: In development environment, both Laravel server and Vite dev server need to run simultaneously.

Open two separate terminals and run the following commands:

**Terminal 1 - Laravel server:**

```bash
php artisan serve
```

**Terminal 2 - Vite dev server (for CSS/JS):**

```bash
npm run dev
```

Alternatively, to run in background:

```bash
# Laravel server in background
nohup php artisan serve --host=127.0.0.1 --port=8000 > /tmp/laravel-serve.log 2>&1 &

# Vite dev server in background
nohup npm run dev > /tmp/vite-dev.log 2>&1 &
```

Note: These steps are for local development. Production setup may require different configuration and permissions.
