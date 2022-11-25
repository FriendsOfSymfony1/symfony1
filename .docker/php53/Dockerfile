FROM buildpack-deps:jessie

ENV PHP_VERSION 5.3.29

# php 5.3 needs older autoconf
RUN set -eux; \
  \
  apt-get update; \
  apt-get install -y \
    curl \
    autoconf2.13 \
  ; \
  rm -r /var/lib/apt/lists/*; \
  \
  curl -sSLfO http://launchpadlibrarian.net/140087283/libbison-dev_2.7.1.dfsg-1_amd64.deb; \
  curl -sSLfO http://launchpadlibrarian.net/140087282/bison_2.7.1.dfsg-1_amd64.deb; \
  dpkg -i libbison-dev_2.7.1.dfsg-1_amd64.deb; \
  dpkg -i bison_2.7.1.dfsg-1_amd64.deb; \
  rm *.deb; \
  \
  curl -sSLf "https://php.net/get/php-$PHP_VERSION.tar.bz2/from/this/mirror" -o php.tar.bz2; \
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
    --with-pdo-mysql \
    --with-zlib \
    --enable-mbstring \
  ; \
  make -j"$(nproc)"; \
  make install; \
  \
  dpkg -r \
    bison \
    libbison-dev \
  ; \
  apt-get purge -y --auto-remove \
    autoconf2.13 \
  ; \
  rm -r /usr/src/php

# Install APC PHP extension
#
RUN set -eux; \
  \
  pecl install apc-3.1.13; \
  echo 'extension=apc.so' >> /usr/local/lib/php.ini; \
  \
  rm -r /tmp/pear;

CMD ["php", "-a"]
