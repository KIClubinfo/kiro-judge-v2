FROM php:7.4-apache
# RUN apt-get update && apt-get uppgrade -y
RUN docker-php-ext-install mysqli
EXPOSE 80

COPY conf_server/php.ini /usr/local/etc/php/php.ini
COPY conf_server/apache.conf /usr/local/apache2/httpd.conf
COPY conf_server/*.sql /docker-entrypoint-initdb.d/