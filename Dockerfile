FROM php:8.1-apache

# Install PHP extensions
# Enable Apache modules
# Set DirectoryIndex to index.php
# Set ServerName to suppress warning
# Fix permissions
RUN docker-php-ext-install mysqli pdo pdo_mysql \
  && a2enmod rewrite dir \
  && sed -i 's/DirectoryIndex .*/DirectoryIndex index.php index.html/' /etc/apache2/mods-enabled/dir.conf \
  && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
  && a2enconf servername

EXPOSE 80
CMD ["apache2-foreground"]
