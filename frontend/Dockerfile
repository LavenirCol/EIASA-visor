FROM yiisoftware/yii2-php:7.4-apache

# Set working directory
WORKDIR /app/web
# Add user for yii application

# Copy existing application directory contents
COPY . /app/web
# Copy existing application directory permissions
COPY --chown=www:www . /app/web
RUN cd ~ && \ chmod 777 -R 
RUN rm -rf /usr/src/php/ext/memcached
RUN apt-get update &&  apt-get install -y --no-install-recommends \
libzip-dev \
zlibc \
zlib1g \
vim \
unzip \
git \
cron \
memcached \
libmemcached-tools \
libmemcached-dev \
&& docker-php-ext-configure zip --with-libzip \
&& docker-php-ext-install zip exif pcntl pdo_mysql mbstring \
&& git clone https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached \
&& cd /usr/src/php/ext/memcached && git checkout -b php7 origin/php7 \
&& docker-php-ext-configure memcached \
&& docker-php-ext-install memcached
RUN echo extension=memcached.so > /usr/local/etc/php/conf.d/memcached.ini
COPY ./docker/php /usr/local/etc/php/conf.d
RUN service memcached start 
RUN pecl install xlswriter && \
docker-php-ext-install sockets 
RUN composer install && \
php init --env=dev --overwrite=All