FROM php:7.2-apache

# because docker caches each new line of command, we should add new packages one per new line to avoid long image rebuilds

RUN apt-get update && \
    apt-get -y install \
        # Required by composer
        git \
        zlib1g-dev \
        libxml2-dev \ 
        unzip \           
        zip \            
    	--no-install-recommends && \
	docker-php-ext-install pdo_mysql && \
	apt-get clean      
        
# Add server config
COPY docker-new-php.conf /etc/apache2/conf-available/

COPY server.pem /etc/ssl/certs/
COPY server.key /etc/ssl/private/

RUN chmod 0600 /etc/ssl/private/server.key

RUN sed -i 's#DocumentRoot.*#DocumentRoot /var/www/html#' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/^#AddDefault/AddDefault/' /etc/apache2/conf-available/charset.conf \
    && sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-available/security.conf \
    && sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf \
    && a2enmod ssl \
    && a2enmod rewrite \
    && a2disconf docker-php \
    && a2enconf docker-new-php

# Install composer
COPY install-composer.sh /install-composer.sh
RUN chmod +x /install-composer.sh
RUN /install-composer.sh && rm /install-composer.sh

# Change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html/points
