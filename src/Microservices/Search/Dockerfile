# Use official PHP runtime as base image
FROM php:7.4-fpm

# Set working directory
WORKDIR /app/src/Microservices/Search/

# Copy source code to working directory
COPY . .

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# install composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer require symfony/console

# Clean Cache
RUN rm -rf /var/cache/apk/*
EXPOSE 80