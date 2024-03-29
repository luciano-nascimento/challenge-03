FROM php:8.0-fpm-alpine

RUN apk add --no-cache openssl \
            bash \
            mysql-client \
            nodejs \
            npm \
            freetype-dev \
            libjpeg-turbo-dev \
            libpng-dev \
            zlib \
            supervisor
            
RUN docker-php-ext-install bcmath && \
        docker-php-ext-install gd && \
        docker-php-ext-install pdo_mysql && \
        CFLAGS="$CFLAGS -D_GNU_SOURCE" docker-php-ext-install sockets

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

WORKDIR /var/www

RUN rm -rf /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN ln -s public html

RUN chown -R www-data:www-data /var/www

# Supervisor
COPY .docker/supervisor/supervisord.conf /etc/supervisord.conf

EXPOSE 9000

ENTRYPOINT ["php-fpm"]