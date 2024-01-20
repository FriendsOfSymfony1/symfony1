FROM buildpack-deps:jessie as php53

ENV PHP_VERSION 5.3.29

RUN set -eux; \
  codename='jessie'; \
  { \
    echo "deb http://archive.debian.org/debian ${codename} main"; \
    echo "deb http://archive.debian.org/debian ${codename}-backports main"; \
    echo "deb http://archive.debian.org/debian-security ${codename}/updates main"; \
  } > /etc/apt/sources.list;

# php 5.3 needs older autoconf
RUN set -eux; \
  \
  buildDeps=' \
    autoconf2.13 \
  '; \
  \
  apt-get update; \
  apt-get install -y --force-yes --no-upgrade --no-install-recommends \
    curl \
    $buildDeps \
  ; \
  rm -r /var/lib/apt/lists/*; \
  \
  curl -sSLfO http://launchpadlibrarian.net/140087283/libbison-dev_2.7.1.dfsg-1_amd64.deb; \
  curl -sSLfO http://launchpadlibrarian.net/140087282/bison_2.7.1.dfsg-1_amd64.deb; \
  dpkg -i libbison-dev_2.7.1.dfsg-1_amd64.deb; \
  dpkg -i bison_2.7.1.dfsg-1_amd64.deb; \
  rm *.deb; \
  \
  curl --insecure -sSLf "https://php.net/get/php-$PHP_VERSION.tar.bz2/from/this/mirror" -o php.tar.bz2; \
  echo 'c4e1cf6972b2a9c7f2777a18497d83bf713cdbecabb65d3ff62ba441aebb0091  php.tar.bz2' | sha256sum -cw --status; \
  \
  mkdir -p /usr/src/php; \
  tar -xf php.tar.bz2 -C /usr/src/php --strip-components=1; \
  rm php.tar.bz2*; \
  \
  cd /usr/src/php; \
  ./buildconf --force; \
  ./configure --disable-cgi \
    $(command -v apxs2 > /dev/null 2>&1 && echo '--with-apxs2' || true) \
    --with-config-file-path=/usr/local/etc/php \
    --with-config-file-scan-dir=/usr/local/etc/php/conf.d \
    --with-libdir=lib/x86_64-linux-gnu \
    --with-pdo-mysql \
    --with-zlib \
    --enable-zip \
    --enable-mbstring \
    --with-openssl=/usr \
    --enable-mysqlnd \
    --with-curl \
    --with-readline \
    --enable-ftp \
  ; \
  make -j"$(nproc)"; \
  make install; \
  install -d /usr/local/etc/php/conf.d; \
  \
  dpkg -r \
    bison \
    libbison-dev \
  ; \
  apt-get purge -y --force-yes --auto-remove -o APT::AutoRemove::RecommendsImportant=true \
    $buildDeps \
  ; \
  rm -r /usr/src/php; \
  apt-get clean; \
  rm -rf /var/lib/apt/lists/*; \
  rm -r /tmp/pear; \
  :;

RUN set -eux; \
  { \
    echo '#! /bin/sh -eu'; \
    echo ''; \
    echo 'echo "extension=${1}.so" > "/usr/local/etc/php/conf.d/docker-php-ext-${1}.ini";'; \
  } | tee /usr/local/bin/docker-php-ext-enable; \
  \
  chmod +x /usr/local/bin/docker-php-ext-enable; \
  :;

CMD ["php", "-a"]

FROM php53

# Install APC PHP extension
#
ARG APC_VERSION
RUN set -eux; \
  \
  packageName=apc; \
  packageVersion=${APC_VERSION}; \
  packageFile=APC-${packageVersion}.tgz; \
  \
  if test x"3.1.13" = x"${packageVersion}"; then \
    packageSha256sum=5ef8ba07729e72946e95951672a5378bed98cb5a294e79bf0f0a97ac62829abd; \
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
  ; \
  rm -r /var/lib/apt/lists/*; \
  :;
