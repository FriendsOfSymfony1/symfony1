ARG PHP_VERSION
FROM php:${PHP_VERSION}-fpm-alpine

COPY --from=composer:latest --link /usr/bin/composer /usr/local/bin/composer
COPY --from=phario/phive:0.15.2 --link /usr/local/bin/phive /usr/local/bin/phive
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install PHP extensions
RUN RUN set -eux; \
    install-php-extensions \
      apcu \
      memcache \
      pdo \
      pdo_mysql \
      zip \
    ;

COPY --link --chmod=0644 php-config/*.ini /usr/local/etc/php/conf.d/

RUN RUN set -eux; \
    apk add file --no-cache;

# Configure Composer folders
RUN RUN set -eux; \
    mkdir /var/composer; \
    chmod a+rwX /var/composer;
