FROM php:7.4-apache


# set our application folder as an environment variable
#ENV APP_HOME /var/www/html

# Arguments defined in docker-compose.yml
#ARG user
#ARG uid
ARG user=appuser
ARG uid=1001

## Copy directories of app from local dir
#COPY App /var/www/App/
##COPY html var/www/html/
#COPY vendor /var/www/vendor/
#COPY config.php /var/www/config.php
#COPY composer.* /var/www/
#COPY *.sh /var/www/
COPY .docker/php_conf/*.ini /usr/local/etc/php/conf.d/
#COPY .docker/php_conf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip 

# PHP extensions
RUN docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \ 
    && docker-php-ext-enable \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd 

# XDEBUG 
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Clear cache
RUN apt-get clean && rm -r /var/lib/apt/lists/* 



# Get latest Composer
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Create system user to run Composer and Artisan Commands
#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user && \
#    chown -R www-data:www-data /var/www && \
#    chmod -R 777 /var/www 
    

# enable apache module mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers
#RUN a2enmod ssl

# To avoid name resolution in apache
#RUN echo "ServerName 127.0.0.1" >> /etc/apache2/apache2.conf


# Set working directory
WORKDIR /var/www

#USER $user

# install all PHP dependencies
#RUN composer install --no-interaction

##RUN docker-compose exec app composer install 
##RUN docker-compose exec app php artisan key:generate
#RUN cd /var/www && composer install && php artisan key:generate

#COPY . /app

#CMD composer update