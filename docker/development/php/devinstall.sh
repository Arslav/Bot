#!/bin/bash
if [[ ${ENV} = "DEV" ]]; then
    echo "Installing xdebug..."
    pecl install xdebug-2.9.2
    echo "xdebug has been installed"

    docker-php-ext-enable xdebug

    echo "Add configurations xdebug to php.ini file..."
    echo "xdebug.remote_host=localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_connect_back=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
    echo "xdebug.remote_log='/tmp/xdebug_log.log'" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
    echo "xdebug configurations has been added"

    pecl install xhprof
    echo "xhprof has been installed"

    echo "Add configurations xdebug to php.ini file..."
    printf "\n[xhprof]\nextension=xhprof.so\nxhprof.output_dir=/tmp" >> $PHP_INI_DIR/php.ini
    echo "xhprof configuration has been added"
    apt install -y graphviz
    echo "graphviz has been installed"
    printf '#!/bin/bash\nln -s /usr/local/lib/php/xhprof_html /app/public/profiler' >> '/link_profiler.sh'
    chmod +x /link_profiler.sh
fi
