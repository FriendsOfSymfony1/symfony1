ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring

# Install APCu PHP extension
#
ARG APCU_VERSION
RUN set -eux; \
  \
  if test x"" = x"${APCU_VERSION}"; then \
    return 0; \
  else :; fi; \
  \
  packageName=apcu; \
  packageVersion=${APCU_VERSION}; \
  \
  packageFile=${packageName}-${packageVersion}.tgz; \
  \
  if test x"5.1.23" = x"${packageVersion}"; then \
    packageSha256sum=67ee7464ccad2335c3fa4aeb0b8edbcf6d8344feea7922620c6a13015d604482; \
  else :; fi; \
  \
  curl --insecure -sSLfO https://pecl.php.net/get/${packageFile}; \
  echo "${packageSha256sum}  ${packageFile}" \
    | sha256sum -cw --status; \
  \
  pecl install ${packageFile}; \
  rm ${packageFile}; \
  \
  docker-php-ext-enable ${packageName}; \
  \
  rm -r /tmp/pear; \
  :;

# Install memcache PHP extension
#
ARG MEMCACHE_VERSION
RUN set -eux; \
  \
  buildDeps=' \
    libzip-dev \
  '; \
  apt-get update; \
  apt-get install -y --force-yes --no-upgrade --no-install-recommends \
    $buildDeps \
  ; \
  \
  packageName=memcache; \
  packageVersion=${MEMCACHE_VERSION}; \
  \
  packageFile=${packageName}-${packageVersion}.tgz; \
  \
  if test x"4.0.5.2" = x"${packageVersion}"; then \
    packageSha256sum=7b7667813baea003671f174bbec849e43ff235a8ea4ab7e36c3a0380c2a9ed63; \
  else :; fi; \
  \
  curl --insecure -sSLfO https://pecl.php.net/get/${packageFile}; \
  echo "${packageSha256sum}  ${packageFile}" \
    | sha256sum -cw --status; \
  \
  pecl install ${packageFile}; \
  rm ${packageFile}; \
  \
  docker-php-ext-enable ${packageName}; \
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

# Install composer
#
RUN set -eux; \
  composerVersion='1.10.27'; \
  installerUrl='https://raw.githubusercontent.com/composer/getcomposer.org/a19025d6c0a1ff9fc1fac341128b2823193be462/web/installer'; \
  \
  curl --insecure -sSLf "${installerUrl}" -o /usr/local/bin/composer-installer.php; \
  echo '203196aedb1a3b0f563363796bbf6f647a4f8c2419bc1dfc5aa45adc1725025d  /usr/local/bin/composer-installer.php' \
    | sha256sum -cw --status; \
  \
  { \
    echo '#! /usr/bin/env php'; \
    cat /usr/local/bin/composer-installer.php; \
  } > /usr/local/bin/composer-installer; \
  rm /usr/local/bin/composer-installer.php; \
  chmod +x /usr/local/bin/composer-installer; \
  \
  composer-installer \
    --disable-tls \
    --version="${composerVersion}" \
    --filename=composer \
    --install-dir=/usr/local/bin \
  ; \
  \
  echo '230d28fb29f3c6c07ab2382390bef313e36de17868b2bd23b2e070554cae23d2  /usr/local/bin/composer' \
    | sha256sum -cw --status; \
  \
  composer --version; \
  \
  apt-get update; \
  apt-get install -y --force-yes --no-upgrade --no-install-recommends \
    git \
    libzip-dev \
    unzip \
  ; \
  rm -r /var/lib/apt/lists/*; \
  \
  docker-php-ext-install zip; \
  :;
