# DUT - Participation and Project Management System

A comprehensive **Participation and Project Management System** designed to collect, evaluate, and manage user-generated suggestions for institutional projects. Built with Laravel and Filament PHP, this platform allows administrators (e.g., municipalities, organizations) to open projects for public feedback, where users can submit ideas, vote, and discuss.

## 📋 Table of Contents

- [Features](#-features)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
  - [Local Development Setup](#local-development-setup)
  - [Production Deployment](#production-deployment)
- [Default Admin Accounts](#-default-admin-accounts)
- [Running the Application](#-running-the-application)
- [Database Management](#-database-management)
- [Troubleshooting](#-troubleshooting)
- [Additional Documentation](#-additional-documentation)

## ✨ Features

- **Admin Panel**: Built with Filament PHP for comprehensive management
- **User & Role Management**: Role-based access control system
- **Project Management**: Create and manage projects with voting capabilities
- **Suggestion System**: Users can submit, vote, and discuss suggestions
- **Multi-language Support**: English, Turkish, French, German, and Swedish
- **Location Hierarchy**: Country → City → District → Neighborhood structure
- **Survey System**: Create surveys with multiple choice and text-based questions
- **Media Management**: Upload and manage images with Spatie Media Library
- **Comment System**: Threaded comments with moderation capabilities

## 💻 System Requirements

Before installation, ensure your system meets the following requirements:

- **PHP**: >= 8.2
- **Composer**: Latest version
- **Node.js**: >= 18.x
- **NPM**: Latest version
- **Database**: MySQL 8.0+ / PostgreSQL 12+ / SQLite 3.35+

### Required PHP Extensions

```bash
# Ubuntu/Debian
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-curl php8.2-mbstring \
  php8.2-xml php8.2-zip php8.2-gd php8.2-mysql php8.2-pgsql php8.2-sqlite3 \
  php8.2-fileinfo php8.2-tokenizer php8.2-pdo

# macOS (using Homebrew)
brew install php@8.2

# Windows
# Download and install PHP 8.2 from php.net
```

## 🚀 Installation

### Local Development Setup

Follow these steps to set up the project on your local development environment:

#### 1. Clone the Repository

```bash
git clone https://github.com/visio-soft/DUT.git
cd DUT
```

#### 2. Install PHP Dependencies

```bash
composer install
```

This will install all required PHP packages including Laravel framework, Filament, and other dependencies.

#### 3. Install JavaScript Dependencies

```bash
npm install
```

This installs frontend dependencies including Vite, Alpine.js, and Tailwind CSS.

#### 4. Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Edit `.env` file and configure your settings:

```env
APP_NAME="DUT Project"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (SQLite for quick setup)
DB_CONNECTION=sqlite

# OR use MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dut
# DB_USERNAME=root
# DB_PASSWORD=

# Optional: Google Translate API (for multi-language support)
# GOOGLE_TRANSLATE_API_KEY=your_api_key_here
# GOOGLE_TRANSLATE_PROJECT_ID=your_project_id_here
```

#### 5. Generate Application Key

```bash
php artisan key:generate
```

This creates a secure encryption key for your application.

#### 6. Database Setup

**For SQLite** (recommended for quick setup):

```bash
# Create SQLite database file
touch database/database.sqlite

# Run migrations
php artisan migrate
```

**For MySQL/PostgreSQL**:

```bash
# Make sure your database is created, then run:
php artisan migrate
```

#### 7. Seed the Database

Populate the database with initial data including admin users, categories, and sample data:

```bash
php artisan db:seed
```

#### 8. Create Storage Link

Create a symbolic link from `public/storage` to `storage/app/public`:

```bash
php artisan storage:link
```

This allows uploaded files to be publicly accessible.

#### 9. Build Frontend Assets (Development)

For development, you can skip building and just run the dev server (see next section).

```bash
npm run dev
```

### Production Deployment

For production deployment, see the [DEPLOYMENT.md](DEPLOYMENT.md) file for detailed instructions including:
- Web server configuration (Apache/Nginx)
- SSL/HTTPS setup
- Performance optimization
- Security best practices

## 🔑 Default Admin Accounts

After running the seeders, you can log in with these accounts:

| Email | Password | Role |
|-------|----------|------|
| admin@admin.com | password | Super Admin |
| omega@admin.com | omega456 | Admin |
| normaladmin@dut.com | main123 | Admin |

**⚠️ Important**: Change these passwords immediately in production!

## ▶️ Running the Application

### Development Mode

In development, you need to run **both** the Laravel server and Vite dev server simultaneously.

#### Option 1: Two Separate Terminals (Recommended)

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server (for hot-reloading CSS/JS):**
```bash
npm run dev
```

Then open your browser and visit: `http://localhost:8000`

#### Option 2: Single Command (Using Composer)

```bash
composer run dev
```

This runs both servers concurrently along with queue listener and log viewer.

#### Option 3: Background Processes

```bash
# Laravel server in background
php artisan serve > /tmp/laravel-serve.log 2>&1 &

# Vite dev server in background  
npm run dev > /tmp/vite-dev.log 2>&1 &
```

### Accessing the Application

- **User Panel**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin

Log in with one of the admin accounts listed above to access the admin panel.

## 🗄️ Database Management

### Running Seeders

Run specific seeder classes:

```bash
# Run a specific seeder
php artisan db:seed --class=CategorySeeder

# Run all seeders
php artisan db:seed
```

### Clean Database and Re-seed

The project includes a helper script to clean the database while preserving specific tables:

```bash
# Using composer
composer seed:clean

# Direct script execution
php scripts/seed_clean.php

# Custom tables to keep and seeders to run
php scripts/seed_clean.php --keep=categories,users --seeders=Database\\\\Seeders\\\\CategorySeeder
```

**⚠️ Warning**: This script truncates tables. Use only in development or with proper backups!

### Fresh Migration (Reset Everything)

```bash
# Drop all tables and re-run migrations
php artisan migrate:fresh

# With seeders
php artisan migrate:fresh --seed
```

## 🔧 Troubleshooting

### Common Issues and Solutions

#### Issue: "Permission denied" errors

```bash
# Fix storage and cache permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux/Apache
```

#### Issue: "Mix manifest not found" or CSS/JS not loading

```bash
# Build assets
npm run build

# Or run dev server
npm run dev
```

#### Issue: Database connection errors

- Check your `.env` file database credentials
- Ensure database service is running
- For SQLite, ensure `database/database.sqlite` file exists and has proper permissions

#### Issue: "Class not found" errors

```bash
# Clear and regenerate autoload files
composer dump-autoload

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### Issue: File upload not working

- Check storage link: `php artisan storage:link`
- Verify storage permissions: `chmod -R 775 storage`
- Check PHP upload limits in `php.ini`:
  - `upload_max_filesize = 50M`
  - `post_max_size = 60M`

### Getting Help

If you encounter issues not covered here:

1. Check the [Laravel Documentation](https://laravel.com/docs)
2. Check the [Filament Documentation](https://filamentphp.com/docs)
3. Review application logs: `storage/logs/laravel.log`

## 📚 Additional Documentation

- **[DEPLOYMENT.md](DEPLOYMENT.md)**: Production deployment guide
- **[PROJECT_HANDOVER.md](PROJECT_HANDOVER.md)**: Complete project overview and features
- **[UPLOAD-README.md](UPLOAD-README.md)**: File upload system documentation

## 🛠️ Development Commands

```bash
# Run tests
composer test

# Code formatting with Laravel Pint
./vendor/bin/pint

# Clear all caches
php artisan optimize:clear

# View logs in real-time
php artisan pail

# Run queue worker
php artisan queue:work
```

## 📝 License

This project is licensed under the MIT License.

---

**Made with ❤️ for participatory governance and community engagement**
