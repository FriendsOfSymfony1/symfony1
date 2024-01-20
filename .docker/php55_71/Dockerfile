ARG PHP_TAG
FROM php:${PHP_TAG}

RUN set -eux; \
  codename='jessie'; \
  { \
    echo "deb http://archive.debian.org/debian ${codename} main"; \
    echo "deb http://archive.debian.org/debian ${codename}-backports main"; \
    echo "deb http://archive.debian.org/debian-security ${codename}/updates main"; \
  } > /etc/apt/sources.list;

RUN set -eux; \
  apt-get update; \
  apt-get install -y --force-yes --no-upgrade --no-install-recommends \
    ca-certificates \
  ; \
  \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*; \
  :;

RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring

# Install APCu PHP extension
#
ARG APCU_VERSION
RUN set -eux; \
  \
  packageName=apcu; \
  packageVersion=${APCU_VERSION}; \
  packageFile=${packageName}-${packageVersion}.tgz; \
  \
  if test x"4.0.11" = x"${packageVersion}"; then \
    packageSha256sum=454f302ec13a6047ca4c39e081217ce5a61bbea815aec9c1091fb849e70b4d00; \
  else :; fi; \
  \
  if test x"5.1.23" = x"${packageVersion}"; then \
    packageSha256sum=67ee7464ccad2335c3fa4aeb0b8edbcf6d8344feea7922620c6a13015d604482; \
  else :; fi; \
  \
  if test x"" = x"${packageVersion}"; then \
    echo "Skip installation of ${packageName} PHP extension"; \
    return 0; \
  fi; \
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
  packageName=memcache; \
  packageVersion=${MEMCACHE_VERSION}; \
  packageFile=${packageName}-${packageVersion}.tgz; \
  \
  if test x"3.0.8" = x"${packageVersion}"; then \
    packageSha256sum=2cae5b423ffbfd33a259829849f6000d4db018debe3e29ecf3056f06642e8311; \
  else :; fi; \
  \
  if test x"4.0.5.2" = x"${packageVersion}"; then \
    packageSha256sum=7b7667813baea003671f174bbec849e43ff235a8ea4ab7e36c3a0380c2a9ed63; \
  else :; fi; \
  \
  if test x"" = x"${packageVersion}"; then \
    echo "Skip installation of ${packageName} PHP extension"; \
    return 0; \
  fi; \
  \
  buildDeps=' \
    libzip-dev \
  '; \
  apt-get update; \
  apt-get install -y --force-yes --no-upgrade --no-install-recommends \
    $buildDeps \
  ; \
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
  apt-get purge -y --force-yes --auto-remove -o APT::AutoRemove::RecommendsImportant=true \
    $buildDeps \
  ; \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*; \
  rm -r /tmp/pear

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
