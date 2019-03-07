FROM richarvey/nginx-php-fpm

WORKDIR /var/www/
RUN rm -rf *

COPY documentation /var/www/
RUN mv public_html html

RUN composer install

EXPOSE 80