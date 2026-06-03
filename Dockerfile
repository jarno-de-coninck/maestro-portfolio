FROM php:8.2-apache

RUN apt-get update && apt-get install -y unzip git default-mysql-client
RUN docker-php-ext-install pdo pdo_mysql \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && echo "pcov.directory=/var/www/html" >> /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html