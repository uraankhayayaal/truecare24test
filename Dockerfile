# PHP Docker image for Yii 2.0 Framework runtime
# ==============================================

FROM php:7.3-fpm-alpine

# Install system packages & PHP extensions required for Yii 2.0 Framework
RUN apk --update --virtual build-deps add \
        autoconf \
        make \
        gcc \
        g++ \
        libtool \
        icu-dev \
        curl-dev \
        freetype-dev \
        imagemagick-dev \
        pcre-dev \
        postgresql-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev && \
    apk add \
        git \
        curl \
        bash \
        bash-completion \
        icu \
        imagemagick \
        pcre \
        freetype \
        libintl \
        libjpeg-turbo \
        libpng \
        libltdl \
        libxml2 \
        libzip \
        mysql-client \
        openssh \
        git \
        postgresql && \
    docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-configure bcmath && \
    docker-php-ext-install \
        zip \ 
        soap \
        curl \
        bcmath \
        exif \
        gd \
        iconv \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        pdo_pgsql && \
    pecl install \
        imagick \
        mongodb && \
    apk del \
        build-deps

RUN echo "extension=imagick.so" > /usr/local/etc/php/conf.d/pecl-imagick.ini && \
    echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/pecl-mongodb.ini


# Configure version constraints
ENV PHP_ENABLE_XDEBUG=0 \
    PATH=/app:/app/vendor/bin:/root/.composer/vendor/bin:$PATH \
    TERM=linux \
    VERSION_PRESTISSIMO_PLUGIN=^0.3.7

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /root/.bashrc && \
        composer

# Add configuration files
COPY dockerconf/ /

WORKDIR /app

ENTRYPOINT ["docker-php-entrypoint"]

#CMD chmod -R 777 /app/web/images/imperavi && \
    #chmod -R 777 /app/web/images/uploads && \
CMD composer update && \
    php yii migrate --interactive=0 && \
    #php tests/bin/yii migrate --interactive=0 && \
    #php vendor/bin/codecept build && \
    #php vendor/bin/codecept run && \
    php-fpm
