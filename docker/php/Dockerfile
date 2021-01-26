FROM php:8.0-fpm-alpine

ARG CURRENT_USER=${CURRENT_USER:-pluecard}
ARG CURRENT_UID=${CURRENT_UID:-1000}
ARG CURRENT_GID=${CURRENT_GID:-1000}

ENV CURRENT_USER=$CURRENT_USER
ENV CURRENT_UID=$CURRENT_UID
ENV CURRENT_GID=$CURRENT_GID

RUN (adduser -h /home/${CURRENT_USER} -D -u ${CURRENT_UID} ${CURRENT_USER} \ 
    && mkdir -p /home/$CURRENT_USER \
    && chown -R "${CURRENT_UID}:${CURRENT_UID}" /home/${CURRENT_USER})

RUN docker-php-ext-install tokenizer mysqli pdo_mysql

RUN apk --update add --no-cache openssh bash \
    && sed -i s/#PermitRootLogin.*/PermitRootLogin\ yes/ /etc/ssh/sshd_config \
    && echo "root:root" | chpasswd \
    && rm -rf /var/cache/apk/* \
    && sed -ie 's/#Port 22/Port 22/g' /etc/ssh/sshd_config \
    && sed -ri 's/#HostKey \/etc\/ssh\/ssh_host_key/HostKey \/etc\/ssh\/ssh_host_key/g' /etc/ssh/sshd_config \
    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_rsa_key/HostKey \/etc\/ssh\/ssh_host_rsa_key/g' /etc/ssh/sshd_config \
    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_dsa_key/HostKey \/etc\/ssh\/ssh_host_dsa_key/g' /etc/ssh/sshd_config \
    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_ecdsa_key/HostKey \/etc\/ssh\/ssh_host_ecdsa_key/g' /etc/ssh/sshd_config \
    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_ed25519_key/HostKey \/etc\/ssh\/ssh_host_ed25519_key/g' /etc/ssh/sshd_config \
    && /usr/bin/ssh-keygen -A \
    && ssh-keygen -t rsa -b 4096 -f  /etc/ssh/ssh_host_key

USER $CURRENT_USER