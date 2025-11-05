# Docker Setup Guide - Joanne's Boutique

This guide will help you containerize and run Joanne's Boutique using Docker and Docker Compose.

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Docker Setup](#docker-setup)
3. [Configuration](#configuration)
4. [Running the Application](#running-the-application)
5. [Development with Docker](#development-with-docker)
6. [Production Deployment](#production-deployment)
7. [Troubleshooting](#troubleshooting)

---

## Prerequisites

### Required Software
- **Docker**: Version 20.10 or higher
- **Docker Compose**: Version 2.0 or higher
- **Git**: For cloning the repository

### Verify Installation
```bash
docker --version
docker-compose --version
```

---

## Docker Setup

### Step 1: Project Structure

Ensure your project has the following Docker files:
```
joannes-boutique/
├── Dockerfile
├── docker-compose.yml
├── .dockerignore
├── .env.example
└── ...
```

### Step 2: Create Dockerfile

Create a `Dockerfile` in the project root:

```dockerfile
# Use official PHP image with Apache
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Copy Apache configuration
COPY docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/public/uploads

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
```

### Step 3: Create docker-compose.yml

Create `docker-compose.yml` in the project root:

```yaml
version: '3.8'

services:
  # PHP Apache Web Server
  web:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: joannes-boutique-web
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
      - ./public/uploads:/var/www/html/public/uploads
    environment:
      - APP_ENV=${APP_ENV:-development}
      - DB_HOST=db
      - DB_PORT=3306
      - DB_NAME=${DB_NAME:-joannes_boutique}
      - DB_USER=${DB_USER:-root}
      - DB_PASSWORD=${DB_PASSWORD:-rootpassword}
    depends_on:
      - db
    networks:
      - joannes-network
    restart: unless-stopped

  # MySQL Database
  db:
    image: mysql:8.0
    container_name: joannes-boutique-db
    ports:
      - "3306:3306"
    environment:
      - MYSQL_ROOT_PASSWORD=${DB_PASSWORD:-rootpassword}
      - MYSQL_DATABASE=${DB_NAME:-joannes_boutique}
      - MYSQL_USER=${DB_USER:-root}
      - MYSQL_PASSWORD=${DB_PASSWORD:-rootpassword}
    volumes:
      - db_data:/var/lib/mysql
      - ./database/joannes_boutique.sql:/docker-entrypoint-initdb.d/init.sql:ro
    networks:
      - joannes-network
    restart: unless-stopped
    command: --default-authentication-plugin=mysql_native_password

  # phpMyAdmin (Optional - for database management)
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: joannes-boutique-phpmyadmin
    ports:
      - "8080:80"
    environment:
      - PMA_HOST=db
      - PMA_USER=root
      - PMA_PASSWORD=${DB_PASSWORD:-rootpassword}
    depends_on:
      - db
    networks:
      - joannes-network
    restart: unless-stopped

volumes:
  db_data:
    driver: local

networks:
  joannes-network:
    driver: bridge
```

### Step 4: Create .dockerignore

Create `.dockerignore` in the project root:

```
.git
.gitignore
.env
.env.*
!.env.example
node_modules
vendor
*.log
*.md
.DS_Store
Thumbs.db
public/uploads/*
!public/uploads/.gitkeep
docker-compose.yml
Dockerfile
.dockerignore
```

### Step 5: Create Apache Configuration

Create `docker/apache-config.conf`:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

---

## Configuration

### Step 1: Environment Variables

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Edit `.env` file:

```env
# Application Environment
APP_ENV=development

# Database Configuration (for Docker)
DB_DRIVER=mysql
DB_HOST=db
DB_PORT=3306
DB_NAME=joannes_boutique
DB_USER=root
DB_PASSWORD=rootpassword

# Timezone
TZ=Asia/Manila
```

**Note**: When using Docker Compose, `DB_HOST` should be `db` (the service name), not `localhost` or `127.0.0.1`.

### Step 2: Build Docker Images

```bash
docker-compose build
```

---

## Running the Application

### Step 1: Start Services

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f web
docker-compose logs -f db
```

### Step 2: Initialize Database

```bash
# Execute migrations
docker-compose exec web php database/migrate.php

# (Optional) Seed sample data
docker-compose exec web php database/seed.php
```

### Step 3: Access Application

- **Web Application**: http://localhost:8000
- **Admin Dashboard**: http://localhost:8000/admin/dashboard
- **phpMyAdmin**: http://localhost:8080 (if enabled)

### Step 4: Verify Installation

```bash
# Check running containers
docker-compose ps

# Check database connection
docker-compose exec web php tools/check_db.php
```

---

## Development with Docker

### Useful Commands

#### Start/Stop Services
```bash
# Start services
docker-compose up -d

# Stop services
docker-compose stop

# Stop and remove containers
docker-compose down

# Stop and remove containers + volumes (WARNING: Deletes database)
docker-compose down -v
```

#### Access Container Shell
```bash
# Access web container
docker-compose exec web bash

# Access database container
docker-compose exec db bash

# Access MySQL CLI
docker-compose exec db mysql -u root -p
```

#### Run Commands Inside Container
```bash
# Run migrations
docker-compose exec web php database/migrate.php

# Run seeds
docker-compose exec web php database/seed.php

# Install Composer dependencies
docker-compose exec web composer install

# Clear cache (if applicable)
docker-compose exec web php artisan cache:clear
```

#### View Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f web
docker-compose logs -f db

# Last 100 lines
docker-compose logs --tail=100 web
```

#### Rebuild After Changes
```bash
# Rebuild without cache
docker-compose build --no-cache

# Rebuild and restart
docker-compose up -d --build
```

### Development Workflow

1. **Make code changes** in your local files
2. **Changes are automatically synced** (via volumes)
3. **Restart service if needed**: `docker-compose restart web`
4. **Check logs** for errors: `docker-compose logs -f web`

### Database Operations

#### Backup Database
```bash
# Export database
docker-compose exec db mysqldump -u root -prootpassword joannes_boutique > backup.sql

# Or using Docker command
docker exec joannes-boutique-db mysqldump -u root -prootpassword joannes_boutique > backup.sql
```

#### Restore Database
```bash
# Import database
docker-compose exec -T db mysql -u root -prootpassword joannes_boutique < backup.sql

# Or using Docker command
docker exec -i joannes-boutique-db mysql -u root -prootpassword joannes_boutique < backup.sql
```

#### Access phpMyAdmin
- URL: http://localhost:8080
- Server: `db`
- Username: `root`
- Password: (from `.env` `DB_PASSWORD`)

---

## Production Deployment

### Production Dockerfile

Create `Dockerfile.prod` for production:

```dockerfile
FROM php:8.1-apache

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application
COPY . /var/www/html/

# Production Apache config
COPY docker/apache-prod.conf /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/public/uploads

# Install dependencies (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Security: Remove sensitive files
RUN rm -rf .git .env.example docker-compose.yml Dockerfile

EXPOSE 80

CMD ["apache2-foreground"]
```

### Production docker-compose.yml

```yaml
version: '3.8'

services:
  web:
    build:
      context: .
      dockerfile: Dockerfile.prod
    container_name: joannes-boutique-web-prod
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./public/uploads:/var/www/html/public/uploads
    environment:
      - APP_ENV=production
      - DB_HOST=${DB_HOST}
      - DB_NAME=${DB_NAME}
      - DB_USER=${DB_USER}
      - DB_PASSWORD=${DB_PASSWORD}
    restart: always
    networks:
      - joannes-network

  db:
    image: mysql:8.0
    container_name: joannes-boutique-db-prod
    environment:
      - MYSQL_ROOT_PASSWORD=${DB_PASSWORD}
      - MYSQL_DATABASE=${DB_NAME}
      - MYSQL_USER=${DB_USER}
      - MYSQL_PASSWORD=${DB_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - joannes-network
    restart: always
    command: --default-authentication-plugin=mysql_native_password

volumes:
  db_data:

networks:
  joannes-network:
    driver: bridge
```

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Use strong database passwords
- [ ] Enable SSL/HTTPS
- [ ] Configure proper firewall rules
- [ ] Set up automated backups
- [ ] Configure log rotation
- [ ] Remove development tools (phpMyAdmin)
- [ ] Set up monitoring
- [ ] Configure reverse proxy (if needed)

---

## Troubleshooting

### Common Issues

#### Port Already in Use
```bash
# Check what's using the port
lsof -i :8000

# Change port in docker-compose.yml
ports:
  - "8080:80"  # Use 8080 instead
```

#### Permission Denied
```bash
# Fix permissions
docker-compose exec web chown -R www-data:www-data /var/www/html
docker-compose exec web chmod -R 755 /var/www/html/public/uploads
```

#### Database Connection Failed
```bash
# Check if database is running
docker-compose ps db

# Check database logs
docker-compose logs db

# Test connection
docker-compose exec web php tools/check_db.php
```

#### Container Won't Start
```bash
# View detailed logs
docker-compose logs web

# Rebuild container
docker-compose build --no-cache web
docker-compose up -d web
```

#### Composer Issues
```bash
# Run composer inside container
docker-compose exec web composer install
docker-compose exec web composer update
```

#### File Changes Not Reflecting
```bash
# Restart web container
docker-compose restart web

# Rebuild if needed
docker-compose up -d --build web
```

### Debug Commands

```bash
# Check container status
docker-compose ps

# Inspect container
docker inspect joannes-boutique-web

# Execute shell in container
docker-compose exec web bash

# Check PHP version
docker-compose exec web php -v

# Check PHP extensions
docker-compose exec web php -m

# Check Apache status
docker-compose exec web apache2ctl status
```

---

## Additional Resources

### Docker Commands Cheat Sheet

```bash
# Build images
docker-compose build

# Start services
docker-compose up -d

# Stop services
docker-compose stop

# View logs
docker-compose logs -f

# Execute commands
docker-compose exec web <command>

# Remove everything
docker-compose down -v

# Clean up
docker system prune -a
```

### Performance Optimization

1. **Use multi-stage builds** for smaller images
2. **Optimize Composer** autoloader
3. **Enable PHP OPcache** in production
4. **Use volume mounts** for development, not production
5. **Implement caching** strategies

### Security Best Practices

1. **Don't commit** `.env` files
2. **Use secrets** for sensitive data
3. **Limit exposed ports** in production
4. **Regular updates** of base images
5. **Scan images** for vulnerabilities: `docker scan`

---

## Support

For issues or questions:
1. Check logs: `docker-compose logs`
2. Review this guide
3. Check Docker documentation
4. Contact development team

---

**Last Updated**: 2025-01-XX  
**Docker Version**: 20.10+  
**Docker Compose Version**: 2.0+

