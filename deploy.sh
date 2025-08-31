#!/bin/bash

# Council ERP - Namecheap Deployment Script
# This script prepares the application for deployment on Namecheap shared hosting

echo "🚀 Starting Council ERP deployment preparation..."

# Set production environment
echo "📝 Setting production environment..."
sed -i 's/APP_ENV=.*/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=.*/APP_DEBUG=false/' .env

# Clear and optimize for production
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
find storage/ -type f -exec chmod 644 {} \;
find bootstrap/cache/ -type f -exec chmod 644 {} \;

# Remove development files
echo "🗑️  Removing development files..."
rm -f check_*.php fix_*.php debug_*.php
rm -rf tests/
rm -f phpunit.xml
rm -f .replit replit.nix

# Optimize autoloader
echo "🏃‍♂️ Optimizing autoloader..."
composer dump-autoload --optimize --no-dev

echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps for Namecheap deployment:"
echo "1. Upload all files to your hosting account"
echo "2. Set document root to point to the 'public' folder"
echo "3. Create MySQL database and update .env file"
echo "4. Run 'php artisan migrate' to set up database"
echo "5. Access your domain to complete installation"
echo ""
echo "📁 Files to upload:"
echo "   - All project files except node_modules/ and tests/"
echo "   - Make sure vendor/ folder is included"
echo "   - Update .env with your database credentials"