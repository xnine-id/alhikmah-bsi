# Stage 1: build (php + composer + node)
FROM php:8.3-fpm-alpine AS build

WORKDIR /var/www/html

# Install system dependencies + nodejs + composer di Alpine
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    linux-headers \
    nodejs \
    npm \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql bcmath pcntl gd sockets zip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV VITE_APP_NAME=VITE_APP_NAME
ENV VITE_REVERB_APP_KEY=VITE_REVERB_APP_KEY
ENV VITE_REVERB_HOST=VITE_REVERB_HOST
ENV VITE_REVERB_PORT=VITE_REVERB_PORT
ENV VITE_REVERB_SCHEME=VITE_REVERB_SCHEME
ENV VITE_FIREBASE_API_KEY=VITE_FIREBASE_API_KEY
ENV VITE_FIREBASE_AUTH_DOMAIN=VITE_FIREBASE_AUTH_DOMAIN
ENV VITE_FIREBASE_PROJECT_ID=VITE_FIREBASE_PROJECT_ID
ENV VITE_FIREBASE_STORAGE_BUCKET=VITE_FIREBASE_STORAGE_BUCKET
ENV VITE_FIREBASE_MESSAGING_SENDER_ID=VITE_FIREBASE_MESSAGING_SENDER_ID
ENV VITE_FIREBASE_APP_ID=VITE_FIREBASE_APP_ID
ENV VITE_FIREBASE_MEASUREMENT_ID=VITE_FIREBASE_MEASUREMENT_ID
ENV VITE_FIREBASE_VAPID_KEY=VITE_FIREBASE_VAPID_KEY

# Install frontend deps
COPY package.json ./
RUN npm i

# Install PHP deps
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-autoloader --no-scripts

# Copy source code
COPY . .

RUN composer dump-autoload -o
RUN php artisan package:discover

# Frontend build
RUN npm run build
RUN rm -rf node_modules

# Stage 2: nginx
FROM nginx:alpine AS nginx

WORKDIR /var/www/html

RUN apk add --no-cache openssl

# Copy only public folder from Laravel
COPY --from=build /var/www/html/public ./public

# Add permissions if needed
RUN chmod -R 755 .

COPY docker/nginx/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]

# Stage 3: production image
FROM php:8.3-fpm-alpine AS production

WORKDIR /var/www/html

# Install runtime dependencies dan build dependencies (pecl redis + extensions)
RUN apk add --no-cache \
    supervisor \
    libpq \
    freetype \
    libjpeg-turbo \
    libpng \
    libzip \
    curl \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
    postgresql-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql bcmath pcntl gd sockets zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Copy built app from build stage
COPY --from=build /var/www/html ./

# Copy supervisor config
COPY docker/app/supervisord.conf /etc/supervisor/supervisord.conf

EXPOSE 9000 8080

COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN touch ./storage/logs/laravel.log
RUN chown -R www-data:www-data ./storage
RUN chown -R www-data:www-data ./bootstrap/cache
RUN chmod -R 775 ./storage
RUN chmod -R 775 ./bootstrap/cache

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
