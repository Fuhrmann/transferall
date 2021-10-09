# syntax=docker/dockerfile:experimental

# ------------------------------------------------------------
# Target base para o PHP
# ------------------------------------------------------------
FROM php:8.0.11-fpm-alpine3.14 as php
LABEL stage=intermediate

# Indica se é para instalar a extensão xdebug
ARG INSTALL_XDEBUG=false

# Indica se é para instalar a extensão opcache
ARG INSTALL_OPCACHE=false

# Define algumas variáveis
ENV \
    USER=www-data \
    UID=1000 \
    GID=1000 \
    TZ='America/Campo_Grande' \
    LANG='pt_BR.UTF-8' \
    LANGUAGE='pt_BR.UTF-8' \
    LC_ALL='pt_BR.UTF-8'

# Instala ferramentas adicionais
RUN apk add --no-cache --virtual .temp \
    # Algumas ferramentas de compilação
    autoconf make g++ \
    # Serve para podermos executar usermod/groupmod
    shadow \
    # Necessário para compilar PHP
    zlib-dev \
&& apk add --no-cache --virtual .deps \
    # Extensão GD do PHP
    libpng-dev jpeg-dev freetype-dev \
    # Extensão ZIP do PHP
    libzip-dev \
    # Extensão intl do PHP
    icu-dev \
    # Para setar a data corretamente (locales)
    tzdata \
    # Necessário para backups
    mariadb-client \
    # Precisamos do git nesse projeto por causa do composer
    git \
#
# Configura a timezone
&& cp /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo "${TZ}" > /etc/timezone \
#
# Instala extensões do PHP
&& docker-php-ext-install mysqli \
    pdo_mysql \
    zip \
    # Laravel precisa dessas extensões abaixo
    bcmath \
    intl \
#
# Instala a extensão do redis
&& pecl install -f redis \
    #
    # Ativa as extensões recém instaladas
    && docker-php-ext-enable redis \
#
# Configura o usuário não root
&& groupmod -o -g ${GID} ${USER} \
    && usermod -o -u ${UID} -g ${USER} ${USER} \
#
# Instala xdebug caso necessário
&& if [ ${INSTALL_XDEBUG} = true ]; then \
    pecl install xdebug && docker-php-ext-enable xdebug \
;fi \
#
# Instala OPCACHE caso necessário
&& if [ ${INSTALL_OPCACHE} = true ]; then \
    docker-php-ext-install opcache \
;fi \
#
# Remove os arquivos temporários
&& apk del .temp \
    && rm -rf /tmp/* \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* /usr/share/doc/* /usr/share/man/* \
#
# Move o arquivo de conf. do PHP
&& mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Muda para o diretório do app
WORKDIR /var/www/html

# Loga como usuário www
USER $USER

# ------------------------------------------------------------
# Target base para o composer (produção)
# ------------------------------------------------------------
FROM composer:2 as composer
LABEL stage=intermediate

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN --mount=type=cache,id=composer,target=/root/.composer composer install \
        --ignore-platform-reqs \
        --no-interaction \
        --no-dev \
        --no-plugins \
        --no-scripts \
        --no-autoloader \
        --prefer-dist

# ------------------------------------------------------------
# Target base para os assets (produção)
# ------------------------------------------------------------
FROM node:14.17.0-alpine3.11 as nodejs
LABEL stage=intermediate
ARG NODE_ENV=production

RUN apk update && apk add python make g++ && rm -rf /var/cache/apk/*
RUN mkdir -p /var/www/html/public
WORKDIR /var/www/html

RUN npm config set scripts-prepend-node-path true

COPY yarn.lock package.json webpack.mix.js ./
RUN set -x \
    && yarn install --production=false \
        --frozen-lockfile \
        --no-cache \
        --no-progress

COPY resources/ resources/
RUN set -x \
    && yarn prod

# ------------------------------------------------------------
# Target final para servidor em produção
# ------------------------------------------------------------
FROM php as producao
LABEL stage=prod

# Copia as configurações do PHP
USER root
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY ./docker/php/php.prod.ini /usr/local/etc/php/conf.d/php.ini
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Copia os arquivos do projeto
USER $USER
COPY --from=composer --chown=www-data:www-data /var/www/html/vendor/ ./vendor/
COPY --from=nodejs --chown=www-data:www-data /var/www/html/public/ ./public/
COPY --chown=www-data:www-data . ./
RUN rm -Rf bootstrap/cache/services.php \
    bootstrap/cache/packages.php

RUN /usr/bin/composer dump-autoload --optimize --classmap-authoritative
