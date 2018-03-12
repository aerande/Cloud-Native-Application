#!/bin/bash

sudo apt-get update -y
sudo apt-get install apache2 php libapache2-mod-php7.0 php-xml php-simplexml php-gd php-mysql zip unzip -y
sudo mv vendor/ /var/www/html/
sudo service apache2 restart
cd /var/www/html
sudo git clone https://aerande:Aniruddha123@github.com/illinoistech-itm/aerande.git
sudo mv aerande/ITMO-544/mp1/Web\ pages/* /var/www/html
sudo rm -rf aerande
sudo mkdir /tmp_grayscale
sudo chmod 777 /tmp_grayscale
cd ~
export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11
curl -sS https://getcomposer.org/installer | php
export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11
php composer.phar require aws/aws-sdk-php
sudo cp -ar  ~/vendor /var/www/html
sudo cp -ar /root/vendor /var/www/html
sudo setfacl -m u:www-data:rwx /home/ubuntu
curl -sS https://getcomposer.org/installer | php