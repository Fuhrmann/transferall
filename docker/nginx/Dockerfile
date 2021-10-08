ARG APP_PROJECT_NAME
FROM ${APP_PROJECT_NAME}-php:prod AS producao

FROM nginx:stable-alpine

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./docker/nginx/files.conf /etc/nginx/shared/files.conf

COPY --from=producao /var/www/html/public /var/www/html/public

WORKDIR /var/www/html
