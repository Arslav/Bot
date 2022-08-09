FROM php:8.1-cli

#install packages
RUN apt-get update && \
    apt-get install -y zlib1g-dev \
                       libzip-dev \
                       libpq-dev \
                       libicu-dev \
                       libpng-dev \
                       libjpeg62-turbo-dev \
                       libxml2-dev \
                       libxslt-dev \
                       wget \
                       libfontconfig1 \
                       libxrender1 \
                       libxtst6 && \
    apt-get clean && \
    apt-get autoclean && \
    apt-get autoremove -y --force-yes && \
    rm -rf /var/lib/apt/lists/*

#install php extentions
RUN pecl install xdebug && \
    docker-php-ext-configure gd --with-jpeg=/usr/lib64 && \
    docker-php-ext-install zip \
                           pdo \
                           pdo_pgsql \
                           intl \
                           gd \
                           xsl && \
    docker-php-ext-enable zip \
                          xdebug

COPY . /web/app/

RUN echo 'xdebug.mode=debug,coverage' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_port=9015' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'session.save_path = "/tmp"' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#composer installation
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /web/app

CMD php
