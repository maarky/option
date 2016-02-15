#!/bin/bash

apt-get install -y language-pack-en-base
LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php -y

apt-get update
apt-get install -y php7.0-cli php-xdebug

cat > /etc/php/7.0/cli/conf.d/30-xdebug_settings.ini << EOF
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_connect_back=1
error_reporting=E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED
EOF

php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '781c98992e23d4a5ce559daf0170f8a9b3b91331ddc4a3fa9f7d42b6d981513cdc1411730112495fbf9d59cffbf20fb2') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); }"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"

cd /vagrant

composer install
