#!/bin/bash
set -e

echo "Waiting for database connection..."
until php artisan db:show > /dev/null 2>&1; do
  echo "Database not ready yet, retrying in 2s..."
  sleep 2
done
echo "Database is up."

# Cache config/routes/views for performance (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations automatically on container start
php artisan migrate --force

# Fix storage symlink (public/storage -> storage/app/public)
php artisan storage:link || true

# Ensure correct permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "Startup tasks complete. Handing off to supervisord..."

exec "$@"