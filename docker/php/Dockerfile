FROM php:8.0-fpm-alpine

ARG CURRENT_USER=${CURRENT_USER:-pluecard}
ARG CURRENT_UID=${CURRENT_UID:-1000}
ARG CURRENT_GID=${CURRENT_GID:-1000}
ARG LOCAL_IP=${LOCAL_IP:-host.docker.internal}

ENV CURRENT_USER=$CURRENT_USER
ENV CURRENT_UID=$CURRENT_UID
ENV CURRENT_GID=$CURRENT_GID
ENV LOCAL_IP=$LOCAL_IP

# Install production dependencies
RUN apk add --no-cache \
    bash \
    curl \
    wget \
    make \
    git \
    openssh

# Install PECL and PEAR  extensions
RUN apk add --no-cache \
    $PHPIZE_DEPS \
    && pecl upgrade timezonedb \
    && pecl install xdebug \
    && rm -rf /tmp/pear

RUN apk --update add --no-cache git

# Enable PECL and PEAR extensions
RUN docker-php-ext-enable xdebug timezonedb

RUN docker-php-source delete

RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >>  /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=docker" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.default_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_host="$LOCAL_IP >> /usr/local/etc/php/conf.d/xdebug.ini

RUN mkdir -p /var/www/html

RUN (adduser -h /home/${CURRENT_USER} -D -u ${CURRENT_UID} ${CURRENT_USER} \ 
    && mkdir -p /home/$CURRENT_USER \
    && chown -R "${CURRENT_UID}:${CURRENT_UID}" /home/${CURRENT_USER})

RUN docker-php-ext-install tokenizer mysqli pdo_mysql

USER $CURRENT_USER