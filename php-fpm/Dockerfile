FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends libpcre3-dev libssl-dev zlib1g-dev curl git unzip libxml2-dev libpq-dev libzip-dev openssl acl librabbitmq-dev libonig-dev

RUN docker-php-ext-install -j$(nproc) zip opcache intl pdo pdo_mysql && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*

RUN pecl install -o -f apcu-5.1.21 && docker-php-ext-enable apcu

RUN pecl install -o -f redis xdebug && docker-php-ext-enable redis apcu xdebug

# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - && apt-get install -y nodejs

# Installing Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/elios

EXPOSE 9000