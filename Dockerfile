FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql curl

# Copy project into a subfolder matching your BASE_URL
COPY . /var/www/html/internal-asset-exchange-platform/

# Grant Apache full access to everything
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf && \
    echo '<Directory /var/www/html/internal-asset-exchange-platform>' >> /etc/apache2/apache2.conf && \
    echo '    AllowOverride All' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf

RUN chmod -R 775 /var/www/html/internal-asset-exchange-platform/public/uploads

EXPOSE 80