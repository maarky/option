#!/bin/bash

apt-get update
apt-get install -y php7.0-cli php7.0-mbstring php7.0-xml php7.0-zip git zip

php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

cd /vagrant

composer install

apt-get install php-xdebug

cat > /etc/php/7.0/cli/conf.d/30-xdebug_settings.ini << EOF
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_connect_back=1
error_reporting=E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED
EOF

ln -s /vagrant /home/vagrant