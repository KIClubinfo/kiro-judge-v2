FROM php:7.4-apache
RUN apt-get update && apt-get upgrade -y
RUN apt install zip unzip -y
RUN docker-php-ext-install mysqli
EXPOSE 80

COPY conf_server/php.ini /usr/local/etc/php/php.ini
COPY conf_server/apache.conf /usr/local/apache2/httpd.conf
COPY conf_server/init.sql /docker-entrypoint-initdb.d/

WORKDIR /var/www/html/
COPY composer_install.sh .
COPY composer.json .
COPY composer.lock .
RUN chmod +x composer_install.sh
RUN ./composer_install.sh
RUN php composer.phar install

RUN apt update && apt upgrade -y
RUN apt install python3 -y
RUN mkdir -p solution_checker
COPY solution_checker/ solution_check/
RUN mkdir -p uploads
RUN chmod -R 777 uploads