FROM php:7.4-apache


# set our application folder as an environment variable
ENV APP_HOME /var/www/html

# Arguments defined in docker-compose.yml
#ARG user
#ARG uid
ARG user=appuser
ARG uid=1001

## Copy directories of app from local dir
COPY ./.docker/php_conf/*.ini /usr/local/etc/php/conf.d/
COPY ./sender/metric-generator.sh /usr/local/bin/metric-generator.sh
COPY ./html/ /var/www/html/

## DEBUG
#COPY .docker/php_conf/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
# PHP extensions
    && docker-php-ext-configure pdo_mysql \
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
    gd \
# enable apache module mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
    && a2enmod rewrite headers \
# XDEBUG 
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
# Clear cache
    && apt-get clean && rm -r /var/lib/apt/lists/* \
# Get latest Composer
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www 

# Create system user to run Composer and Artisan Commands
#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer && \
#    chown -R $user:$user /home/$user && \



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