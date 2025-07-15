# --- ESTÁGIO 1: BUILDER ---
FROM php:8.2-fpm-alpine AS builder

# Instala dependências do sistema e extensões PHP
RUN apk add --no-cache \
    build-base \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-dev \
    nodejs \
    npm

# Configura e instala as extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql zip bcmath gd

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# --- MUDANÇA IMPORTANTE AQUI ---
# 1. Copia os arquivos de dependência primeiro para aproveitar o cache do Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

COPY package.json package-lock.json ./
RUN npm install

# 2. AGORA, copia o restante do código da aplicação
COPY . .

# 3. Com todos os arquivos no lugar, AGORA podemos rodar o build do frontend
RUN npm run build

# 4. Otimiza o Laravel para produção
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# --- ESTÁGIO 2: PRODUÇÃO ---
FROM php:8.2-fpm-alpine

# Instala apenas as bibliotecas de runtime essenciais
RUN apk add --no-cache libzip libpng libjpeg-turbo freetype postgresql-libs

# Instala as extensões PHP
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS build-base libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath gd \
    && apk del .build-deps

WORKDIR /app

# Copia os arquivos "buildados" do estágio anterior
COPY --from=builder /app .

# Ajusta as permissões das pastas
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]