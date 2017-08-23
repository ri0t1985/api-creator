FROM ubuntu:16.04

MAINTAINER Paul Edenburg

# you need to install 'locales' for utf8 to be able to add ppa:ondrej which uses cyrillic characters in its name
RUN apt-get update \
    && apt-get install -y curl zip unzip git software-properties-common vim mysql-client openssh-client supervisor sendmail locales

RUN locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.1-fpm php7.1-cli php7.1-mcrypt php7.1-gd php7.1-mysql \
       php7.1-pgsql php7.1-imap php-memcached php7.1-mbstring php7.1-xml php7.1-curl \
       php7.1-sqlite3 php7.1-xdebug \
       npm \
    && mkdir /run/php \
    && apt-get remove -y --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl https://getcomposer.org/composer.phar -o /usr/bin/composer
RUN chmod +x /usr/bin/composer

RUN echo "set -o vi" >> ~/.bashrc && \
    echo "alias ll='ls -l'" >> ~/.bashrc

COPY xdebug.ini /etc/php/7.1/mods-available/xdebug.ini

COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

WORKDIR /var/www/html

EXPOSE 80
CMD ["start-container"]
