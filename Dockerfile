# --- ESTÁGIO 1: BUILDER (agora só para dependências) ---
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

# Copia e instala dependências de backend e frontend
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

COPY package.json package-lock.json ./
RUN npm install

# --- ESTÁGIO 2: IMAGEM FINAL DE PRODUÇÃO ---
# NOMEAMOS ESTE ESTÁGIO PARA 'production'
FROM php:8.2-fpm-alpine AS production

# Instala apenas as bibliotecas de runtime essenciais
RUN apk add --no-cache libzip libpng libjpeg-turbo freetype postgresql-libs

# Instala as extensões PHP
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS build-base libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath gd \
    && apk del .build-deps

WORKDIR /app

# Copia apenas as dependências pré-instaladas do estágio anterior
COPY --from=builder /app/vendor /app/vendor
COPY --from=builder /app/node_modules /app/node_modules

# Copia o restante da aplicação
COPY . .

# Ajusta as permissões das pastas
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache
RUN chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]