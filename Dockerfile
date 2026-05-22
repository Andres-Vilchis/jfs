FROM php:8.2-apache

# Extensiones necesarias para CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install intl mysqli pdo pdo_mysql zip

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar archivos
COPY . /var/www/html

WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permisos
RUN chown -R www-data:www-data writable
RUN chmod -R 775 writable

# Apache apunta a /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
