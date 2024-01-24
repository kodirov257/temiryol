FROM joseluisq/php-fpm:8.3

RUN apk add gnupg
RUN apk add --update linux-headers

COPY ["conf.d/05-apcu-recommended.ini", "conf.d/05-opcache-recommended.ini", "conf.d/05-security-recommended.ini", "/usr/local/etc/php/conf.d/"]

# Configure opcache
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN sed -i 's/;opcache.enable=1/opcache.enable=0/g' /usr/local/etc/php/php.ini
RUN echo 'max_execution_time=120' >> /usr/local/etc/php/conf.d/php.ini
RUN echo 'memory_limit=512M' >> /usr/local/etc/php/conf.d/php.ini

# Install composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Xdebug
RUN apk update \
    && apk add --no-cache $PHPIZE_DEPS

COPY cron_commands /etc/cron.d/cron-commands

RUN chmod 0644 /etc/cron.d/cron-commands

RUN crontab /etc/cron.d/cron-commands

RUN touch /var/log/cron.log

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"
COPY ./error_reporting.ini "${PHP_INI_DIR}/conf.d"

EXPOSE 9000
#CMD ["php-fpm", "-F"]
CMD php-fpm & crond -l 2 -f
