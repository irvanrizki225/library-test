#Dockerfile
FROM php:8.2-fpm

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
    libicu-dev \
    libpq-dev \
    git

RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        pcntl \
        gd \
        zip \
        intl

# Install Node.js dan npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first untuk optimasi cache
COPY composer.json composer.lock ./

# Sekarang copy seluruh aplikasi
COPY . .

# Install PHP dependencies lebih awal
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Build assets (jalankan hanya jika package.json ada)
RUN [ -f package.json ] && npm install && npm run build || echo "No package.json, skipping npm install"
