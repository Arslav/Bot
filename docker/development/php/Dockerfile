FROM php:8.1-fpm

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
RUN docker-php-ext-configure gd --with-jpeg=/usr/lib64 && \
    docker-php-ext-install zip \
                           pdo \
                           pdo_pgsql \
                           intl \
                           gd \
                           xsl && \
    docker-php-ext-enable zip \
                          gd

#copy files
COPY ./docker/development/php/devinstall.sh devinstall.sh
COPY ./docker/development/php/default.ini /usr/local/etc/php/conf.d/default.ini
COPY ./app/ /web/app/

ARG ENV

#install xdebug
RUN chmod u+x devinstall.sh && \
    ./devinstall.sh

#composer installation
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet
ENV COMPOSER_ALLOW_SUPERUSER 1

#run
WORKDIR /web/app
RUN chmod u+x /web/app/bin/console
RUN chmod u+x /web/app/bin/doctrine
CMD php-fpm
