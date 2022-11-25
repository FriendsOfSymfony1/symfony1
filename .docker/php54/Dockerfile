FROM php:5.4-cli

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring

# Install APC PHP extension
#
RUN set -eux; \
  pecl install apc-3.1.13; \
  docker-php-ext-enable apc; \
  rm -r /tmp/pear;

# Install memcache PHP extension
#
ARG MEMCACHE_VERSION
RUN set -eux; \
  buildDeps=' \
    libzip-dev \
  '; \
  apt-get update; \
  apt-get install -y --no-upgrade --no-install-recommends \
    $buildDeps \
  ; \
  \
  pecl install memcache-${MEMCACHE_VERSION}; \
  docker-php-ext-enable memcache; \
  \
  apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=true \
    $buildDeps \
  ; \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*; \
  rm -r /tmp/pear
