# Dockerfile - PHP 8.2 + Apache + Laravel + Node.js + SSL
FROM php:8.2-apache

# Katalog roboczy w kontenerze
WORKDIR /var/www/html

# Instalacja zależności systemowych + Node.js + PHP extensions + Apache mod
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    openssl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring bcmath intl zip gd \
    && a2enmod rewrite ssl \
    && a2ensite default-ssl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Apache -> public/ jako DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf

# Kopiowanie własnej konfiguracji SSL (zostanie użyta montowana ścieżka)
COPY docker/apache/ssl.conf /etc/apache2/sites-available/default-ssl.conf
RUN a2ensite default-ssl

# Composer z oficjalnego obrazu
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Kopiowanie kodu aplikacji
COPY . .

# Uprawnienia do katalogów
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Composer + frontend
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install
RUN npm run build

# Eksponowanie portów HTTP + HTTPS
EXPOSE 80 443

# Uruchomienie Apache w pierwszym planie
CMD ["apache2-foreground"]
