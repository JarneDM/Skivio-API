#!/bin/bash
set -e
# Default to port 8080 if PORT environment variable is not set
PORT=${PORT:-8080}

# Modify Apache to listen on the specified port
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf

# Update the VirtualHost to the specified port
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf

# Start Apache
exec apache2-foreground
