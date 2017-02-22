FROM ubuntu:xenial
MAINTAINER AlbertMorenoDEV

ENV DEBIAN_FRONTEND noninteractive
ENV COMPOSER_DISABLE_XDEBUG_WARN 1

RUN PACKAGES_TO_INSTALL="sudo git cron php7.1-dev php7.1-intl php7.1-xml composer php-xdebug php7.1-zip php7.1-mbstring php7.1-curl php7.1-fpm nginx supervisor libyaml-dev php7.1-mysql openssh-server iputils-ping" && \
    apt-get update && \
    apt-get install -y software-properties-common && \
    apt-add-repository -y ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y --allow-unauthenticated $PACKAGES_TO_INSTALL && \
    apt-get autoremove -y && \
    apt-get clean && \
    apt-get autoclean

# configure NGINX as non-daemon
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# configure php-fpm as non-daemon
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/7.1/fpm/php-fpm.conf

# clear apt cache and remove unnecessary packages
RUN apt-get autoclean && apt-get -y autoremove


# NGINX mountable directory for apps
WORKDIR /var/www

# copy config file for Supervisor
ADD templates/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ADD templates/xdebug.ini /etc/php/7.1/mods-available/xdebug.ini

# backup default default config for NGINX
RUN mv /etc/nginx/sites-available/default /etc/nginx/sites-available/default.bak

# copy local defualt config file for NGINX
ADD templates/nginx-site.conf /etc/nginx/sites-available/default

# php7.0-fpm will not start if this directory does not exist
RUN mkdir /run/php

RUN mkdir /var/run/sshd
RUN echo 'root:amd_catalog' | chpasswd
RUN sed -i 's/PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

# SSH login fix. Otherwise user is kicked off after login
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd

ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile

EXPOSE 22 80

CMD ["/usr/bin/supervisord"]