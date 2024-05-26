# Use an official PHP runtime as a parent image
FROM php:7.4-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# Install mysqli extension for PHP
RUN docker-php-ext-install mysqli

# Install MySQL client
RUN apt-get update && apt-get install -y default-mysql-client

# Expose port 80 to the outside world
EXPOSE 80
