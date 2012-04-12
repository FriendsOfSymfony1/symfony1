#!/bin/bash

wget http://pecl.php.net/get/APC-3.1.10.tgz
tar -xzf APC-3.1.10.tgz
sh -c "cd APC-3.1.10 && phpize && ./configure --enable-apc && make && sudo make install"
echo "extension=apc.so
apc.enabled = 1
apc.enable_cli = 1" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

git submodule update --init --recursive
php data/bin/check_configuration.php
