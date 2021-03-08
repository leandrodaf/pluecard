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

RUN docker-php-ext-install tokenizer mysqli pdo_mysql

ADD docker/php/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN mkdir -p /var/www/html

RUN (adduser -h /home/${CURRENT_USER} -D -u ${CURRENT_UID} ${CURRENT_USER} \ 
    && mkdir -p /home/$CURRENT_USER \
    && chown -R "${CURRENT_UID}:${CURRENT_UID}" /home/${CURRENT_USER})


USER $CURRENT_USER