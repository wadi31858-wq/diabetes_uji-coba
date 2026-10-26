# ==============================================================================
# Dockerfile: Hybrid PHP 8.3 (Laravel 13) + Python 3 (XGBoost ML & Streamlit)
# Production-ready for Render, Railway, Fly.io, and VPS
# ==============================================================================
FROM php:8.3-apache

WORKDIR /var/www/html

# 1. Install system utilities and Python 3
RUN apt-get update && apt-get install -y --no-install-recommends \
    python3 \
    python3-pip \
    python3-venv \
    libzip-dev \
    libsqlite3-dev \
    unzip \
    git \
    curl \
    dos2unix \
    && rm -rf /var/lib/apt/lists/*

# 2. Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo pdo_sqlite zip bcmath

# 3. Enable Apache modules (mod_rewrite and proxy for Streamlit)
RUN a2enmod rewrite proxy proxy_http proxy_wstunnel

# 4. Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Create Python Virtual Environment & Install ML Dependencies
ENV VIRTUAL_ENV=/opt/venv
RUN python3 -m venv $VIRTUAL_ENV
ENV PATH="$VIRTUAL_ENV/bin:$PATH"
ENV PYTHON_PATH="$VIRTUAL_ENV/bin/python"

COPY requirements.txt ./
RUN pip install --no-cache-dir --upgrade pip && \
    pip install --no-cache-dir -r requirements.txt

# 6. Copy Apache configuration
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/ports.conf /etc/apache2/ports.conf

# 7. Copy Entrypoint script and ensure Unix line endings
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# 8. Copy application source code
COPY . .

# 9. Set permissions and prepare folders
RUN mkdir -p storage bootstrap/cache database && \
    touch database/database.sqlite && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache database

# 10. Install PHP Composer dependencies (production mode)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Default Environment Variables
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite
ENV PYTHON_PATH=/opt/venv/bin/python
ENV STREAMLIT_URL=/streamlit-embed/

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
