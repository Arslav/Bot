FROM php:8.1.9-cli-alpine

#install packages
RUN apk update
RUN apk upgrade
RUN apk add --no-cache --quiet --no-progress \
    freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev libwebp-dev libzip-dev \
    postgresql-dev libxslt-dev icu-data-full php8-zip php8-mbstring php8-xml php8-curl php8-pdo php8-pdo_pgsql \
    php8-intl php8-pecl-imagick php8-gd php8-dom wget autoconf g++ make

#configure extenstions
RUN pecl install xdebug && \
    docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp && \
    docker-php-ext-install zip \
                           pdo \
                           pdo_pgsql \
                           intl \
                           gd \
                           xsl && \
    docker-php-ext-enable zip \
                          gd \
                          xdebug

#configure xdebug
RUN echo "Add configurations xdebug to php.ini file..." && \
    echo 'xdebug.mode=debug,coverage' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_port=9003' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'session.save_path = "/tmp"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug configurations has been added"

#composer installation
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PHP_IDE_CONFIG "serverName=Docker"

#copy files
COPY ./ /web/app
WORKDIR /web/app