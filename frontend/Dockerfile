FROM yiisoftware/yii2-php:7.4-apache

# Set working directory
WORKDIR /app/web
# Add user for yii application
#RUN groupadd -g 1000 www
#RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /app/web
# Copy existing application directory permissions
COPY --chown=www:www . /app/web
RUN cd ~ && chmod 777 -R /app/