FROM alterway/php:8.1-fpm

RUN apt-get update && apt-get install -y supervisor cron && apt-get clean

RUN mkdir -p /var/log/supervisor

COPY --chown=root:root ./docker/config/supervisord.conf /etc/supervisor/supervisord.conf
COPY --chown=root:crontab --chmod=0600 ./docker/config/cron /var/spool/cron/crontabs/root
