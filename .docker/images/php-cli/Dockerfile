FROM php:7.2-cli-alpine

RUN apk upgrade --update && apk add \
    # Compiler deps
    coreutils  && \
    # Compile extensions without dependencies
    docker-php-ext-install bcmath mbstring opcache pdo pdo_mysql json && \
    # Libraries / dependencies for other extensions
    apk add \
    # zlib
    zlib-dev \
    # gd
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    # intl
    icu-dev \
    # curl
    curl-dev && \
    # Configure gd
    docker-php-ext-configure gd \
        --with-jpeg-dir=/usr/local/share/ \
        --with-png-dir=/usr/local/share/ \
        --with-freetype-dir=/usr/include/ && \
    # Compile extensions with dependencies
    docker-php-ext-install zip gd intl curl

# Possible values for ext-name:
# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl
# json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql
# pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets sodium spl
# standard sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zend_test zip

RUN adduser -D -u 1000 php-cli php-cli

# Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer

# Front-end stuff (only needed during DEV!)
RUN apk add nodejs nodejs-npm && npm install --global yarn


USER php-cli

WORKDIR /var/www/html/wrapper

CMD ["tail", "-f", "/dev/null"]