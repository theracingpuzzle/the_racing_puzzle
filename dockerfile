FROM php:8.2-apache

# Install Node.js and other dependencies
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    git \
    unzip \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# If you have a composer.json file, uncomment the following lines:
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer install --no-interaction

# If you have package.json for Node.js, uncomment:
# RUN npm install
# RUN npm run build

# Create database directory for SQLite
RUN mkdir -p /data
RUN chmod -R 777 /data

# Configure Apache virtual host
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Make sure Apache listens on the port Render expects
ENV PORT=8080
RUN echo 'Listen ${PORT}' > /etc/apache2/ports.conf

# Make the entrypoint script
RUN echo '#!/bin/bash\n\
sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Start Apache
CMD ["/usr/local/bin/entrypoint.sh"]