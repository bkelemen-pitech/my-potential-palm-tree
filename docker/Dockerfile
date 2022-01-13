FROM php:7.4-apache

# Install additional packages needed by the app.
RUN apt-get update && apt-get install -y curl git zip unzip openssh-client nano vim libssh-dev libpng-dev \
zip unzip zlib1g-dev libpq-dev libmcrypt-dev libzip-dev libicu-dev --no-install-recommends
RUN docker-php-ext-configure intl
RUN docker-php-ext-install gd pdo pdo_pgsql zip gettext intl bcmath

ADD php.ini /usr/local/etc/php/conf.d/php.ini

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get autoremove -y && apt-get clean && apt-get autoclean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP Redis extension.
RUN pecl install redis && docker-php-ext-enable redis

# Install Xdebug.
RUN pecl install xdebug && docker-php-ext-enable xdebug
ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Disable default VH on apache
RUN a2dissite 000-default.conf
RUN a2enmod rewrite

# Copy and enable the VH
COPY app.conf /etc/apache2/sites-available/
RUN a2ensite app.conf
RUN service apache2 restart
