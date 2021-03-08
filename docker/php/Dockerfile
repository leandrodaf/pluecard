FROM php:8.0-fpm-alpine

ARG CURRENT_USER=${CURRENT_USER:-pluecard}
ARG CURRENT_UID=${CURRENT_UID:-1000}
ARG CURRENT_GID=${CURRENT_GID:-1000}

ENV CURRENT_USER=$CURRENT_USER
ENV CURRENT_UID=$CURRENT_UID
ENV CURRENT_GID=$CURRENT_GID

# Install PECL and PEAR  extensions
RUN apk add --no-cache git openssh \
    $PHPIZE_DEPS \
    && pecl install xdebug

RUN apk --update add --no-cache git

# Enable PECL and PEAR extensions
RUN docker-php-ext-enable xdebug

RUN docker-php-source delete

RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN mkdir -p /var/www/html

RUN (adduser -h /home/${CURRENT_USER} -D -u ${CURRENT_UID} ${CURRENT_USER} \ 
    && mkdir -p /home/$CURRENT_USER \
    && chown -R "${CURRENT_UID}:${CURRENT_UID}" /home/${CURRENT_USER})

RUN docker-php-ext-install tokenizer mysqli pdo_mysql

USER $CURRENT_USER