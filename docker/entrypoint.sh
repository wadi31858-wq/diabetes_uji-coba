#!/bin/bash
set -e

# Support dynamic port binding from Cloud Providers (Railway/Render default to $PORT)
PORT="${PORT:-8080}"
echo "Configuring Apache to listen on port ${PORT}..."
sed -i "s/Listen [0-9]*/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Setup SQLite Database
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating database.sqlite..."
    touch /var/www/html/database/database.sqlite
fi

# Ensure storage directories exist
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Generate Application Key if not provided
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force || true
fi

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Run Database Migrations & Seeds
echo "Running database migrations..."
php artisan migrate --force --graceful || true
php artisan db:seed --force || true

# Cache configurations in production
if [ "$APP_ENV" = "production" ]; then
    echo "Optimizing Laravel configuration & routes for production..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Optionally start Streamlit in background for the embedded analytics hub
if [ "$ENABLE_STREAMLIT" != "false" ] && [ -f "/var/www/html/app.py" ]; then
    echo "Starting Streamlit Analytics Engine in background on port 8501..."
    streamlit run /var/www/html/app.py \
        --server.port 8501 \
        --server.address 127.0.0.1 \
        --server.headless true \
        --browser.gatherUsageStats false &
fi

echo "Application is ready! Starting Apache web server..."
exec apache2-foreground
