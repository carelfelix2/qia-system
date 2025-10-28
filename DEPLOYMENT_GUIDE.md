# QIA System Deployment Guide

## Overview
This guide provides step-by-step instructions for deploying the QIA System Laravel application to Hostinger shared hosting.

## Prerequisites
- Hostinger shared hosting account
- cPanel access
- MySQL database: `u1119694_qia_db`
- Database user: `u1119694_carelu1119694`
- Database password: `FlukeBiomedical01`
- Domain: `hub.quantum-inti.co.id`
- PHP 8.3+ support

## Pre-deployment Preparation

### 1. Environment Configuration
- Production `.env.production` file has been created with the following settings:
  - APP_ENV=production
  - APP_DEBUG=false
  - APP_KEY=base64:WktSs8Fft/z9GCy7B6fDHZywP6qBePvbxHaHY3UJAEg=
  - Database connection configured for MySQL
  - URL set to https://hub.quantum-inti.co.id

### 2. Application Optimization
- Configuration cached (`php artisan config:cache`)
- Routes cached (`php artisan route:cache`)
- Views cached (`php artisan view:cache`)
- Composer dependencies optimized (`composer install --no-dev --optimize-autoloader`)
- Assets built for production (`npm run build`)

### 3. File Permissions
- Storage link created (`php artisan storage:link`)
- File permissions set for web server access (IUSR full control on storage directories)

## Deployment Steps

### Step 1: Upload Files to Hosting
1. Create a ZIP archive of the entire project (excluding development files)
2. Upload the ZIP file to your Hostinger hosting via cPanel File Manager or FTP
3. Extract the ZIP file in the public_html directory (or subdomain directory if using a subdomain)

### Step 2: Environment Setup
1. Copy `.env.production` to `.env` in the root directory
2. Ensure the `.env` file has the correct database credentials and app settings

### Step 3: Database Setup
1. Access phpMyAdmin through cPanel
2. Create the database `u1119694_qia_db` (if not already created)
3. Import the database schema by running migrations:
   ```bash
   php artisan migrate --force
   ```

### Step 4: Additional Setup
1. Run database seeders if needed:
   ```bash
   php artisan db:seed --force
   ```

2. Clear and cache configuration:
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

3. Set proper file permissions:
   - Storage directory: 755
   - Bootstrap/cache directory: 755
   - All files: 644

### Step 5: Verify Deployment
1. Access the application at https://hub.quantum-inti.co.id
2. Check that all pages load correctly
3. Test user authentication and role-based access
4. Verify file uploads work (profile photos, quotation attachments)

## Post-deployment Checklist
- [ ] Application loads without errors
- [ ] Database connection works
- [ ] User authentication functions
- [ ] File uploads work
- [ ] Email notifications (if configured)
- [ ] All user roles (admin, sales, teknisi, inputer_sap) work correctly
- [ ] Storage link is accessible
- [ ] Assets (CSS/JS) load correctly

## Troubleshooting
- If you encounter 500 errors, check Laravel logs in `storage/logs/`
- Ensure PHP version is 8.3 or higher
- Verify database credentials in `.env`
- Check file permissions on storage directories
- Clear application cache if needed: `php artisan cache:clear`

## Security Notes
- APP_DEBUG is set to false in production
- APP_KEY is properly generated
- File permissions are set appropriately
- Database credentials are secured

## Maintenance
- Regularly update dependencies
- Monitor logs for errors
- Backup database regularly
- Keep PHP version updated
