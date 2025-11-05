# Standard Operating Procedure (SOP) - Joanne's Boutique

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Development Setup](#development-setup)
4. [Database Management](#database-management)
5. [Deployment Procedures](#deployment-procedures)
6. [Maintenance Tasks](#maintenance-tasks)
7. [Troubleshooting](#troubleshooting)
8. [Security Procedures](#security-procedures)
9. [Backup and Recovery](#backup-and-recovery)

---

## Project Overview

### Purpose
Joanne's Boutique is a full-featured e-commerce and booking platform for managing a boutique specializing in gowns, suits, and event packages.

### Technology Stack
- **Backend**: PHP 8.1+
- **Database**: MySQL/MariaDB
- **Frontend**: Tailwind CSS, Alpine.js, Font Awesome
- **Web Server**: Apache/Nginx (or PHP built-in server)
- **Package Manager**: Composer

### Key Features
- Product gallery with search and filtering
- Package catalog management
- Customer booking system (rentals, events)
- Shopping cart and checkout
- Payment proof upload (GCash)
- Admin dashboard for management
- Testimonial/review system
- User authentication and authorization

---

## System Architecture

### Directory Structure
```
joannes-boutique/
├── admin/              # Admin-specific entry point
├── api/                # API endpoints
├── config/             # Configuration files
├── database/           # Database migrations and seeds
├── public/             # Public web root
│   ├── assets/         # Static assets (images, videos)
│   └── uploads/        # User-uploaded files
├── src/                # Application source code
│   ├── Controllers/    # MVC Controllers
│   ├── Models/         # Data models
│   ├── Utils/          # Utility classes
│   └── Views/          # View templates
└── tools/              # Utility scripts
```

### Core Components

#### Controllers
- `HomeController`: Homepage and public pages
- `ProductController`: Product gallery and details
- `PackageController`: Package management
- `AuthController`: Authentication (login, register, profile)
- `BookingController`: Booking management
- `PaymentController`: Payment processing
- `AdminController`: Admin dashboard operations
- `CategoryController`: Category management
- `TestimonialController`: Review management
- `ContactController`: Contact form handling

#### Models
- `User`: User management
- `Product`: Product catalog
- `Category`: Product categories
- `Package`: Event packages
- `BookingOrder`: Booking and rental orders
- `Order`: E-commerce orders
- `Cart`: Shopping cart
- `Testimonial`: Customer reviews
- `ContactMessage`: Contact form submissions

#### Utilities
- `Database`: Database connection management
- `Auth`: Authentication and authorization
- `CSRF`: CSRF protection

---

## Development Setup

### Prerequisites
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx) or PHP built-in server
- Git

### Initial Setup Steps

#### 1. Clone Repository
```bash
git clone <repository-url>
cd joannes-boutique
```

#### 2. Install Dependencies
```bash
composer install
```

#### 3. Environment Configuration
Create `.env` file in project root:
```env
# Application Environment
APP_ENV=development

# Database Configuration
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=joannes_boutique
DB_USER=root
DB_PASS=
DB_PASSWORD=

# Timezone
TZ=Asia/Manila
```

#### 4. Database Setup
```bash
# Run migrations
php database/migrate.php

# (Optional) Seed sample data
php database/seed.php
```

#### 5. Directory Permissions
```bash
# Set upload directory permissions
chmod -R 755 public/uploads
chmod -R 755 public/uploads/categories
```

#### 6. Start Development Server
```bash
# Option 1: PHP built-in server
php -S localhost:8000 -t public

# Option 2: Using Composer
composer start

# Option 3: XAMPP (Windows)
# Place project in htdocs/joannes-boutique
# Access via: http://localhost/joannes-boutique/public
```

### Access Points
- **Public Site**: `http://localhost:8000` or `http://localhost/joannes-boutique/public`
- **Admin Dashboard**: `http://localhost:8000/admin/dashboard`
- **Database**: `127.0.0.1:3306`

---

## Database Management

### Database Schema

#### Core Tables
- `users`: User accounts (admin/customer)
- `categories`: Product categories
- `products`: Product catalog
- `product_images`: Additional product images
- `packages`: Event packages
- `booking_orders`: Rental and package bookings
- `orders`: E-commerce orders
- `order_items`: Order line items
- `cart`: Shopping cart items
- `testimonials`: Customer reviews
- `contact_messages`: Contact form submissions
- `bookings`: Appointment bookings

### Migration Procedures

#### Running Migrations
```bash
php database/migrate.php
```

#### Manual SQL Execution
```bash
mysql -u root -p joannes_boutique < database/joannes_boutique.sql
```

#### Feature-Specific Migrations
```bash
# Booking availability feature
mysql -u root -p joannes_boutique < database/2025_11_booking_availability.sql

# Penalty system feature
mysql -u root -p joannes_boutique < database/2025_11_penalty_feature.sql
```

### Database Maintenance

#### Backup
```bash
# Full backup
mysqldump -u root -p joannes_boutique > backup_$(date +%Y%m%d).sql

# Structure only
mysqldump -u root -p --no-data joannes_boutique > structure.sql

# Data only
mysqldump -u root -p --no-create-info joannes_boutique > data.sql
```

#### Restore
```bash
mysql -u root -p joannes_boutique < backup_20250101.sql
```

#### Optimization
```bash
# Analyze tables
mysql -u root -p -e "USE joannes_boutique; OPTIMIZE TABLE users, products, booking_orders;"

# Check table status
mysql -u root -p -e "USE joannes_boutique; SHOW TABLE STATUS;"
```

---

## Deployment Procedures

### Pre-Deployment Checklist
- [ ] Update `.env` with production values
- [ ] Set `APP_ENV=production`
- [ ] Run database migrations
- [ ] Verify file permissions
- [ ] Test all functionality
- [ ] Backup current database
- [ ] Review security settings

### Production Environment Setup

#### 1. Server Requirements
- PHP 8.1+ with extensions: `pdo_mysql`, `mbstring`, `gd`, `fileinfo`
- MySQL 5.7+ or MariaDB 10.3+
- Apache 2.4+ or Nginx 1.18+
- SSL certificate (recommended)

#### 2. Apache Configuration
```apache
<VirtualHost *:80>
    ServerName joannes-boutique.com
    DocumentRoot /var/www/joannes-boutique/public
    
    <Directory /var/www/joannes-boutique/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName joannes-boutique.com
    DocumentRoot /var/www/joannes-boutique/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/joannes-boutique/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### 3. Nginx Configuration
```nginx
server {
    listen 80;
    server_name joannes-boutique.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name joannes-boutique.com;
    root /var/www/joannes-boutique/public;
    index index.php;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }
}
```

#### 4. Deployment Steps
```bash
# 1. Pull latest code
git pull origin main

# 2. Install/update dependencies
composer install --no-dev --optimize-autoloader

# 3. Run migrations
php database/migrate.php

# 4. Set permissions
chmod -R 755 public/uploads
chown -R www-data:www-data public/uploads

# 5. Clear caches (if any)
# (Not applicable for this project currently)

# 6. Restart web server
sudo systemctl restart apache2
# OR
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
```

---

## Maintenance Tasks

### Daily Tasks
- [ ] Monitor error logs
- [ ] Check for failed payment uploads
- [ ] Verify booking confirmations
- [ ] Review pending testimonials

### Weekly Tasks
- [ ] Database backup
- [ ] Review server logs
- [ ] Check disk space usage
- [ ] Update product inventory
- [ ] Verify payment proofs

### Monthly Tasks
- [ ] Database optimization
- [ ] Security updates
- [ ] Performance review
- [ ] Backup verification
- [ ] Clean old uploads (if needed)

### Code Updates
```bash
# 1. Create backup
mysqldump -u root -p joannes_boutique > backup_$(date +%Y%m%d).sql

# 2. Pull updates
git pull origin main

# 3. Update dependencies
composer install --no-dev

# 4. Run migrations
php database/migrate.php

# 5. Test functionality
# (Manual testing required)

# 6. Restart services if needed
```

---

## Troubleshooting

### Common Issues

#### Database Connection Error
**Symptoms**: "Database connection failed"
**Solutions**:
1. Verify `.env` database credentials
2. Check MySQL service is running: `sudo systemctl status mysql`
3. Verify database exists: `mysql -u root -p -e "SHOW DATABASES;"`
4. Check firewall settings

#### File Upload Errors
**Symptoms**: Images not uploading
**Solutions**:
1. Check `public/uploads` permissions: `chmod -R 755 public/uploads`
2. Verify `php.ini` settings:
   - `upload_max_filesize = 10M`
   - `post_max_size = 10M`
3. Check disk space: `df -h`

#### Session Issues
**Symptoms**: Users logged out unexpectedly
**Solutions**:
1. Check session directory permissions
2. Verify session configuration in `php.ini`
3. Check session storage: `ls -la /var/lib/php/sessions`

#### 404 Errors
**Symptoms**: Pages not found
**Solutions**:
1. Verify `.htaccess` exists in `public/` directory
2. Check Apache mod_rewrite is enabled
3. Verify `BASE_URL` configuration
4. Check route definitions in `public/index.php`

#### Performance Issues
**Symptoms**: Slow page loads
**Solutions**:
1. Enable PHP OPcache
2. Optimize database queries
3. Check server resources: `top`, `htop`
4. Review slow query log
5. Consider adding caching layer

### Debug Mode
Enable debug mode in `.env`:
```env
APP_ENV=development
```

### Error Logs
- **PHP Errors**: Check `/var/log/php/error.log` or `php_error.log`
- **Apache Errors**: `/var/log/apache2/error.log`
- **Nginx Errors**: `/var/log/nginx/error.log`
- **Application Logs**: Check `public/find_error_log.php`

---

## Security Procedures

### Security Checklist

#### Application Security
- [ ] Use strong passwords for admin accounts
- [ ] Enable HTTPS/SSL
- [ ] Regular security updates
- [ ] CSRF protection enabled
- [ ] SQL injection prevention (using prepared statements)
- [ ] XSS protection (input sanitization)
- [ ] File upload validation
- [ ] Session security (secure, httponly cookies)

#### Server Security
- [ ] Firewall configured (UFW/iptables)
- [ ] SSH key authentication only
- [ ] Regular system updates
- [ ] Disable unnecessary services
- [ ] Configure fail2ban
- [ ] Monitor access logs

#### Database Security
- [ ] Use strong database passwords
- [ ] Limit database user privileges
- [ ] Regular backups
- [ ] Encrypted connections (if remote)
- [ ] No direct database access from web

### Security Updates
```bash
# Update PHP
sudo apt update && sudo apt upgrade php8.1

# Update system packages
sudo apt update && sudo apt upgrade

# Check for vulnerabilities
composer audit
```

### Password Policy
- Minimum 8 characters
- Mix of uppercase, lowercase, numbers
- Admin accounts require stronger passwords
- Regular password rotation recommended

---

## Backup and Recovery

### Backup Strategy

#### Full Backup Script
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/joannes-boutique"
DB_NAME="joannes_boutique"
DB_USER="root"
DB_PASS="your_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# File backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    /var/www/joannes-boutique

# Keep only last 30 days
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete

echo "Backup completed: $DATE"
```

#### Automated Backup (Cron)
```bash
# Add to crontab (crontab -e)
0 2 * * * /path/to/backup.sh >> /var/log/backup.log 2>&1
```

### Recovery Procedures

#### Database Recovery
```bash
# Stop application
sudo systemctl stop apache2

# Restore database
mysql -u root -p joannes_boutique < backup_20250101.sql

# Verify restoration
mysql -u root -p -e "USE joannes_boutique; SHOW TABLES;"

# Restart application
sudo systemctl start apache2
```

#### File Recovery
```bash
# Extract backup
tar -xzf files_20250101.tar.gz -C /tmp

# Copy files back
cp -r /tmp/var/www/joannes-boutique/* /var/www/joannes-boutique/

# Set permissions
chown -R www-data:www-data /var/www/joannes-boutique
chmod -R 755 /var/www/joannes-boutique/public/uploads
```

### Disaster Recovery Plan
1. **Identify**: Determine scope of disaster
2. **Assess**: Evaluate backup availability
3. **Restore**: Restore from most recent backup
4. **Verify**: Test all functionality
5. **Document**: Record incident and resolution

---

## Additional Resources

### Documentation Files
- `README.md`: Project overview and quick start
- `QUICK_REFERENCE.md`: Quick commands and references
- `BOOKING_FLOW_ANALYSIS.md`: Booking system documentation
- `PENALTY_SYSTEM_IMPLEMENTATION.md`: Penalty feature docs
- `SYSTEM_ANALYSIS.md`: System architecture details

### Support Contacts
- **Technical Support**: [Your contact information]
- **Database Issues**: [DBA contact]
- **Server Issues**: [DevOps contact]

### Version Information
- **PHP Version**: 8.1+
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Framework**: Custom PHP MVC
- **Last Updated**: 2025-01-XX

---

**Document Version**: 1.0  
**Last Updated**: 2025-01-XX  
**Maintained By**: Development Team

