FROM php:8.1-fpm

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip libpq-dev \
    # FOR DOCKER POSTGRESQL INSTALL
      #&& docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
      #&& docker-php-ext-install intl opcache pdo pdo_pgsql \
    # END POSTGRESQL 
    # FOR MYSQL INSTALL
      #&& docker-php-ext-install mysqli pdo pdo_mysql \
      #&& docker-php-ext-enable pdo_mysql \
    # END MYSQL
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /var/www/slim_app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd ../ composer require slim/slim:"4.*" \slim/psr7 \ selective/basepath
RUN cd ../ composer require laminas/laminas-diactoros

RUN git config --global user.email "jpickett76@gmail.com" \
    && git config --global user.name "jpickett76"
