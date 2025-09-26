# DUT Project Deployment Guide

## Production Requirements

### PHP Extensions Required

```bash
# Ubuntu/Debian
sudo apt install php8.2-gd php8.2-fileinfo php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip

# CentOS/RHEL
sudo yum install php-gd php-fileinfo php-mbstring php-xml php-curl

# Arch Linux
sudo pacman -S php-gd
```

### Deployment Steps

1. **Clone Repository**

```bash
git clone <repository-url>
cd DUT
git checkout main # or production branch
```

2. **Install Dependencies**

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

3. **Environment Setup**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure Environment (.env)**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# File Upload Settings (adjust based on server)
UPLOAD_MAX_FILESIZE=50M
POST_MAX_SIZE=60M
```

5. **Database Setup**

```bash
php artisan migrate --force
php artisan db:seed --force
```

6. **Storage & Permissions**

```bash
php artisan storage:link
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache # For Apache/Nginx
```

7. **Cache Optimization**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

8. **Web Server Configuration**

### Apache (.htaccess already included)

```apache
<VirtualHost *:80>
    DocumentRoot /path/to/DUT/public
    ServerName yourdomain.com

    <Directory /path/to/DUT/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/DUT/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # File upload size limits
    client_max_body_size 60M;
}
```

## Features & Functionality

### Large File Upload System

-   **Chunked Upload**: Automatically handles files >1.5MB
-   **PHP Limits**: Configurable upload limits
-   **Progress Tracking**: Real-time upload progress
-   **Error Handling**: Retry mechanism for failed chunks

### Admin Panel

-   **Filament Admin**: `/admin` route
-   **User Management**: Role-based permissions
-   **Content Management**: Projects, suggestions, categories
-   **Media Management**: Image upload and storage

### File Storage

-   **Media Library**: Spatie Media Library integration
-   **Collections**: Organized file storage
-   **Optimization**: Automatic image processing (optional)

## Troubleshooting

### File Upload Issues

1. Check PHP limits: `php -i | grep upload`
2. Check web server limits (Nginx: `client_max_body_size`)
3. Check storage permissions: `ls -la storage/`
4. Check logs: `tail -f storage/logs/laravel.log`

### Performance Optimization

1. Enable OPcache in production
2. Use Redis/Memcached for sessions/cache
3. Enable gzip compression
4. Use CDN for static assets

### Security

-   Always use HTTPS in production
-   Keep dependencies updated: `composer update`
-   Regular backups of database and storage
-   Monitor logs for suspicious activity

## Git Pull Deployment

For seamless deployment with `git pull`:

1. **Setup deployment script**:

```bash
#!/bin/bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

2. **Run deployment**:

```bash
chmod +x deploy.sh
./deploy.sh
```

## Monitoring

-   **Logs**: `storage/logs/laravel.log`
-   **Queue Jobs**: Use supervisor for queue workers
-   **Cron Jobs**: Set up for scheduled tasks
-   **Health Checks**: Monitor `/` endpoint

## Support

For issues or questions, check:

-   Laravel documentation: https://laravel.com/docs
-   Filament documentation: https://filamentphp.com/docs
