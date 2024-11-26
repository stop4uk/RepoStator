FROM php:8.1-fpm AS base

ENV APP_HOME /var/www/html
ENV USERNAME=www-data

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get upgrade -y && apt-get install -y \
      procps \
      nano \
      mc \
      git \
      unzip \
      supervisor \
      cron \
      sudo \
      zip \
      locales \
   && sed -i '/en_US.UTF-8/s/^# //g' /etc/locale.gen && locale-gen \
   && chmod +x /usr/local/bin/install-php-extensions && install-php-extensions \
      gd \
      pdo_mysql \
      sockets \
      intl \
      bcmath \
      opcache \
      zip \
      http \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

#Timezone and locales
RUN cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime

ENV LANG=ru_RU.UTF-8
ENV LANGUAGE=ru_RU:UTF-8
ENV LC_ALL=ru_RU.UTF-8

#PHP config
COPY ./docker/config/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/config/php.ini /usr/local/etc/php/php.ini

#Composer
COPY --from=composer:latest --chmod=755 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

#Supervisor
RUN mkdir -p /var/log/supervisor
COPY --chown=root:root ./docker/config/supervisord.conf /etc/supervisor/supervisord.conf
COPY --chown=root:crontab --chmod=0600 ./docker/config/cron /var/spool/cron/crontabs/root

FROM base

#Copy source and config files
ADD --chown=${USERNAME}:${USERNAME} application $APP_HOME

#Set working directory
WORKDIR $APP_HOME

RUN for DIR in 'backend console frontend'; do \
      if [ -d $APP_HOME/$DIR/runtime ]; then chown -R ${USERNAME}:${USERNAME} $APP_HOME/$DIR/runtime; fi \
    done

USER ${USERNAME}

#PHP dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 COMPOSER_CACHE_DIR=/tmp composer install --optimize-autoloader --no-interaction --no-progress;

USER root
