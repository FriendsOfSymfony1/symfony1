FROM php:5.4-cli

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

# Install APC PHP extension
#
RUN set -eux; \
  \
  packageName=APC; \
  packageVersion=3.1.13; \
  \
  packageFile=${packageName}-${packageVersion}.tgz; \
  \
  packageSha256sum=5ef8ba07729e72946e95951672a5378bed98cb5a294e79bf0f0a97ac62829abd; \
  \
  curl --insecure -sSLfO https://pecl.php.net/get/${packageFile}; \
  echo "${packageSha256sum}  ${packageFile}" \
    | sha256sum -cw --status; \
  \
  pecl install ${packageFile}; \
  rm ${packageFile}; \
  \
  echo "extension=apc.so" >> /usr/local/lib/php.ini; \
  \
  rm -r /tmp/pear; \
  :;

# Install memcache PHP extension
#
ARG MEMCACHE_VERSION
RUN set -eux; \
  \
  if test x"3.0.8" = x"${MEMCACHE_VERSION}"; then \
      packageSha256sum=2cae5b423ffbfd33a259829849f6000d4db018debe3e29ecf3056f06642e8311; \
  else :; fi; \
  \
  buildDeps=' \
    libzip-dev \
  '; \
  apt-get update; \
  apt-get install -y --force-yes --no-upgrade --no-install-recommends \
    $buildDeps \
  ; \
  \
  curl --insecure -sSLfO https://pecl.php.net/get/memcache-${MEMCACHE_VERSION}.tgz; \
  echo "${packageSha256sum}  memcache-${MEMCACHE_VERSION}.tgz" \
    | sha256sum -cw --status; \
  \
  pecl install memcache-${MEMCACHE_VERSION}.tgz; \
  rm memcache-${MEMCACHE_VERSION}.tgz; \
  \
  docker-php-ext-enable memcache; \
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
