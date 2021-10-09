#!/bin/sh
until docker logs ${APP_PROJECT_NAME}-mysql 2>&1 | grep -o "ready for connections" ; do
  >&2 echo "Aguardando conexão com o banco de dados..."
  sleep 3
done
sleep 4
