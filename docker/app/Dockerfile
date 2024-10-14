FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libpcre3-dev \
    libzip-dev \
    postgresql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pgsql pdo_pgsql zip exif pcntl
RUN docker-php-ext-configure gd --enable-gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-png=/usr/include/
RUN docker-php-ext-install gd

RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis
RUN pecl install -o -f oauth-2.0.4 && rm -rf /tmp/pear && docker-php-ext-enable oauth

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www

COPY --chown=www:www app        /var/www/app
COPY --chown=www:www bootstrap  /var/www/bootstrap
COPY --chown=www:www config     /var/www/config
COPY --chown=www:www database   /var/www/database
COPY --chown=www:www resources  /var/www/resources
COPY --chown=www:www routes     /var/www/routes
COPY --chown=www:www storage    /var/www/storage
COPY --chown=www:www tests      /var/www/tests
COPY --chown=www:www artisan    /var/www/artisan

COPY --chown=www:www public/index.php /var/www/public/index.php

COPY --chown=www:www composer.lock composer.json /var/www/

RUN mkdir vendor && chown www:www vendor

USER www

RUN composer install --no-cache

EXPOSE 9000
CMD ["php-fpm"]
