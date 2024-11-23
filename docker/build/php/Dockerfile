FROM alterway/php:8.1-fpm

RUN apt-get update && apt-get install -y supervisor cron && apt-get clean

RUN mkdir -p /var/log/supervisor