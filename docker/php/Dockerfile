FROM php:8.1.8-fpm
#
# update package list
RUN apt update
# usefull tools
RUN apt install -y apt-utils nano htop git ncdu zip unzip libzip-dev libmcrypt-dev
# gd
RUN apt install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
# mbstring
RUN apt install -y libonig5 libonig-dev
RUN docker-php-ext-install mbstring
# intl
RUN apt install -y libicu-dev
RUN docker-php-ext-install intl
# bz2
RUN apt install -y libbz2-dev pv
RUN docker-php-ext-install bz2
# soap
RUN apt install -y libxml2-dev
RUN docker-php-ext-install soap
# redis
RUN pecl install -o -f redis
RUN rm -rf /tmp/pear
RUN docker-php-ext-enable redis
# general extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli zip gettext
# imagick
RUN apt install -y libmagickwand-dev --no-install-recommends
RUN echo '' | pecl install imagick
RUN docker-php-ext-enable imagick

# Install symfony cli
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt install -y symfony-cli

# Install yarn
RUN apt install -y yarn

# Install npm for tailwind
RUN apt install -y npm

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# update package list
RUN apt -y update
RUN apt -y upgrade

#
# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# copy config of php to extend limits
COPY docker/php/local.ini /usr/local/etc/php/conf.d/

# This makes directory /var/www owned by www-data which is the default user for php-fpm.
RUN chown -R www-data:www-data /var/www


# for solve user problem
# https://www.digitalocean.com/community/tutorials/how-to-set-up-laravel-nginx-and-mysql-with-docker-compose-on-ubuntu-20-04
# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY src/ /var/www/html/

# Copy existing application directory permissions
# COPY --chown=www:www . /var/www

# Change current user to www
# USER www

RUN composer install --prefer-install=dist

# Expose port 9000 and start php-fpm server
# EXPOSE 9000
# CMD ["php-fpm"]
