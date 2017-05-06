FROM ubuntu:latest
MAINTAINER Matteo Merola <mattmezza@gmail.com>

# Add let's encrypt repo
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get -y install software-properties-common
RUN add-apt-repository ppa:certbot/certbot

# Install apache, PHP, and supplimentary programs. openssh-server, curl, and lynx-cur are for debugging the container.
RUN apt-get update && apt-get -y upgrade && DEBIAN_FRONTEND=noninteractive apt-get -y install \
    apache2 php7.0 php7.0-mysql libapache2-mod-php7.0 curl lynx-cur php7.0-cli git python-certbot-apache zip

# Enable apache mods.
RUN a2enmod php7.0
RUN a2enmod rewrite
RUN a2enmod ssl

# Update the PHP.ini file, enable <? ?> tags and quieten logging.
RUN sed -i "s/short_open_tag = Off/short_open_tag = On/" /etc/php/7.0/apache2/php.ini
RUN sed -i "s/error_reporting = .*$/error_reporting = E_ERROR | E_WARNING | E_PARSE/" /etc/php/7.0/apache2/php.ini

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
# Set the server name for the virtual host
ENV SERVER_NAME serverone.datasounds.io

# Adding server name to hosts
RUN echo "${SERVER_NAME}    localhost" >> /etc/hosts

# Expose apache.
EXPOSE 80

# Copy this repo into place.
ADD . /var/www/site

# Install the app through composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cd /var/www/site && composer install

# Update the default apache site with the config we created.
ADD config/apache-config.conf /etc/apache2/sites-enabled/000-default.conf
ADD config/apache-config.conf /etc/apache2/sites-enabled/default-ssl.conf

# set up renewal
# Add crontab file in the cron directory
ADD config/letsencrypt_cron /etc/cron.d/letsencrypt_cron
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/letsencrypt_cron

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND