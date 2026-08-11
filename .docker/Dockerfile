ARG PHP_VERSION

FROM alpine:3.19.1 as php-cs-fixer

RUN set -eux; \
  version=3.52.1; \
  \
  apk add --no-cache curl; \
  \
  curl -sSLfo /usr/local/bin/php-cs-fixer \
    https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/releases/download/v${version}/php-cs-fixer.phar; \
  \
  chmod +x /usr/local/bin/php-cs-fixer; \
  :;


FROM php:${PHP_VERSION}-cli

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql

# Install mbstring PHP extension
#
RUN set -eux; \
  apt-get update; \
  apt-get install -y --no-upgrade --no-install-recommends \
    libonig-dev \
  ; \
  \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*; \
  \
  docker-php-ext-install mbstring

# Install APCu PHP extension
#
ARG APCU_VERSION
RUN set -eux; \
  \
  test x"" = x"${APCU_VERSION}" || { \
    pecl install apcu-${APCU_VERSION}; \
    docker-php-ext-enable apcu; \
    \
    rm -r /tmp/pear; \
  }

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

# For consistent mime type file guesser
RUN set -eux; \
  distFilePath=`which file`; \
  \
  mv ${distFilePath} ${distFilePath}.dist; \
  { \
    echo '#! /bin/sh -eu'; \
    echo ''; \
    echo "${distFilePath}"'.dist "$@" | sed -e s,application/x-pie-executable,application/x-executable,g'; \
  } | tee ${distFilePath}; \
  \
  chmod +x ${distFilePath}; \
  \
  file /bin/ls --mime | grep application/x-executable; \
  :;

COPY --from=php-cs-fixer /usr/local/bin/php-cs-fixer /usr/local/bin/php-cs-fixer
