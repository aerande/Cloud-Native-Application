#!/bin/bash

sudo service apache2 restart
cd /var/www/html
sudo git clone git@github.com:illinoistech-itm/aerande.git
sudo mv aerande/ITMO-544/mp1/Web\ pages/* /var/www/html
sudo rm -rf aerande
sudo mkdir /tmp_grayscale
sudo chmod 777 /tmp_grayscale
cd ~
export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11
export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update 1.0.0-alpha11