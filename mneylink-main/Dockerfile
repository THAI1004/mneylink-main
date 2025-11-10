FROM php:7.4-apache

RUN apt-get update && apt-get install -y libicu-dev libonig-dev && \
    pecl install redis && \
    docker-php-ext-install pdo_mysql mysqli intl bcmath mbstring && \
    a2enmod rewrite headers ssl

# Set default working directory

# COPY . /var/www/html
# COPY docker-php-entrypoint /usr/local/bin/docker-php-entrypoint
# RUN chown -R www-data:www-data /var/www/html


# # # Create tmp directory and make it writable by the web server
# RUN mkdir -p \
#     tmp/cache/models \
#     tmp/cache/persistent \
#   && chown -R :www-data \
#     tmp \
#   && chmod -R 770 \
COPY docker-php-entrypoint /usr/local/bin/
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
