FROM wordpress:php8.2-fpm

LABEL maintainer="your@email.com"
LABEL description="WordPress PHP-FPM 8.2 with WooCommerce optimizations"

# ── Install System Dependencies ───────────────────────────────
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    libzip-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libfreetype6-dev \
    libmagickwand-dev \
    fcgi \
    && rm -rf /var/lib/apt/lists/*

# ── Configure & Install PHP Extensions ───────────────────────
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        mysqli \
        opcache \
        pdo_mysql \
        zip \
    && pecl install \
        imagick \
        redis \
    && docker-php-ext-enable \
        imagick \
        redis

# ── Install WP-CLI ────────────────────────────────────────────
ARG WPCLI_VERSION=2.10.0
RUN curl -sL \
    "https://github.com/wp-cli/wp-cli/releases/download/v${WPCLI_VERSION}/wp-cli-${WPCLI_VERSION}.phar" \
    -o /usr/local/bin/wp \
    && chmod +x /usr/local/bin/wp \
    && wp --info

# ── Create Log Directory ──────────────────────────────────────
RUN mkdir -p /var/log/php && chown www-data:www-data /var/log/php

# ── Copy WP-CLI Entrypoint ────────────────────────────────────
COPY docker-entrypoint-wpcli.sh /usr/local/bin/docker-entrypoint-wpcli.sh
RUN chmod +x /usr/local/bin/docker-entrypoint-wpcli.sh

WORKDIR /var/www/html
