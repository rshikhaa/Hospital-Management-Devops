FROM php:8.2-fpm

# Install Nginx + dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www

# Copy project
COPY . .

# ✅ Create required Laravel directories BEFORE composer
RUN mkdir -p bootstrap/cache storage \
    && chmod -R 775 bootstrap storage \
    && chown -R www-data:www-data bootstrap storage

# ✅ Now install dependencies
RUN composer install --no-dev --optimize-autoloader

# Nginx config
RUN echo 'server { \
    listen 80; \
    root /var/www/public; \
    index index.php; \
    location / { try_files $uri $uri/ /index.php?$query_string; } \
    location ~ \.php$ { fastcgi_pass 127.0.0.1:9000; fastcgi_index index.php; include fastcgi_params; fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; } \
}' > /etc/nginx/sites-available/default

# Start script
RUN echo '#!/bin/bash\nnginx\nphp-fpm' > /start.sh && chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]