#!/bin/bash

svn export http://svn.php.net/repository/pecl/apc/trunk/ apc-trunk
sh -c "cd apc-trunk && phpize && ./configure --enable-apc && make && sudo make install"
echo "extension=apc.so
apc.enabled = 1
apc.enable_cli = 1" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

git submodule update --init --recursive
php data/bin/check_configuration.php
