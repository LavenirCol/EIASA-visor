FROM yiisoftware/yii2-php:7.4-apache
WORKDIR /app/web
COPY . /app/web
RUN cd /app/web 
RUN chmod 777 /app/web/ 
RUN rm -rf /usr/src/php/ext/memcached
RUN apt-get update &&  apt-get install -y --no-install-recommends \
libzip-dev \
zlibc \
zlib1g \
vim \
unzip \
git \
cron \
wget \
memcached \
libmemcached11 \
libmemcachedutil2 \
libmemcached-dev \
libz-dev \
zip \
libonig-dev 
RUN mkdir -p /usr/src/php/ext/memcached
WORKDIR /usr/src/php/ext/memcached
RUN wget https://github.com/php-memcached-dev/php-memcached/archive/v3.1.3.zip; 
RUN unzip /usr/src/php/ext/memcached/v3.1.3.zip
RUN mv /usr/src/php/ext/memcached/php-memcached-3.1.3/* /usr/src/php/ext/memcached/
COPY ./docker/php /usr/local/etc/php/conf.d
RUN docker-php-ext-install sockets
ENTRYPOINT /app/web/docker/run/start.sh ; /bin/bash