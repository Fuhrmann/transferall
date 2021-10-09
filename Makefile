# Define o target padrão ao executar `make`
.DEFAULT_GOAL = help

# Define o usuário e grupo
DOCKER_UID=$(shell id -u):$(shell id -g)

# Arquivos do compose para ambiente desenv
DEV_COMPOSE=-f docker-compose.yml -f docker-compose.dev.yml

# Arquivos do compose para ambiente produção
PROD_COMPOSE=-f docker-compose.yml -f docker-compose.prod.yml

# Arquivos do compose para expor as portas dos serviços
PORTS_COMPOSE=-f docker-compose.ports.yml

# Arquivos do compose com serviços de vida limitada
SERVICES_COMPOSE=${DEV_COMPOSE} -f docker-compose.services.yml

# Data e hora atuais
DATE := $(shell date '+%Y-%m-%d-%H-%M-%S')

# Inclui o arquivo .env caso ele exista
ENV_FILE := $(strip $(wildcard .env))
ifeq ($ENV_FILE,)
	$(error Arquivo .env nao encontrado! Por favor, crie ele)
else
	include .env
endif

# Define o ambiente a ser utilizado (por padrão desenv)
COMPOSE_FILE=${DEV_COMPOSE}
ifeq ($(APP_ENV), production)
	COMPOSE_FILE=${PROD_COMPOSE}
endif

# Define se iremos expor as portas
ifdef DOCKER_NGINX_PORT
	COMPOSE_FILE:=${COMPOSE_FILE} ${PORTS_COMPOSE}
endif

# Pega os argumentos passados
args = `arg="$(filter-out strip $@,$(MAKECMDGOALS))" && echo $${arg:-${1}}`

# Define alguns aliases
php = docker-compose ${COMPOSE_FILE} exec -T php
redis = docker-compose ${COMPOSE_FILE} exec redis
composer = docker-compose ${SERVICES_COMPOSE} run --user ${DOCKER_UID} --rm composer
yarn = docker-compose ${SERVICES_COMPOSE} run --user ${DOCKER_UID} --rm yarn

d: ## Executa um comando genérico utilizando o docker (make d exec php sh)
	@docker-compose ${args}

up: ## Inicia os containers
	${INFO} "[docker] Iniciando containers..."
	@COMPOSE_DOCKER_CLI_BUILD=1 \
    docker-compose ${COMPOSE_FILE} up -d

ifeq ($(APP_ENV), production)
	@docker-compose ${COMPOSE_FILE} exec php php artisan optimize
endif

copy-env:
	@cp .env.example .env

wait-for-mysql:
	@APP_PROJECT_NAME=${APP_PROJECT_NAME} ./docker/bin/wait-for-mysql.sh

install: copy-env composer-install yarn-install up wait-for-mysql key migrate seed ## Instala o projeto local do zero
	${INFO} "[docker] Aplicação inicializada com sucesso!"

up-build: ## Inicia os containers e recria-os caso necessário
	${INFO} "[docker] Iniciando containers e recriando o que precisar..."
	@COMPOSE_DOCKER_CLI_BUILD=1 \
	docker-compose ${COMPOSE_FILE} up -d --build ${args}

ps: ## Exibe os containers em execução
	@docker-compose ${COMPOSE_FILE} ps

down: confirmar ## Para todos os containers
	${INFO} "[docker] Parando todos os containers..."
	@docker-compose ${COMPOSE_FILE} down

restart: confirmar ## Reinicia todos os containers
	${INFO} "[docker] Reiniciando containers..."
	@docker-compose ${COMPOSE_FILE} restart ${args}

connect-db: ## Conecta no banco de dados
	${INFO} "[docker] Conectando no banco de dados [mysql]..."
	@docker-compose ${COMPOSE_FILE} exec mysql mysql -u${DB_USERNAME} -p${DB_PASSWORD}

logs: ## Exibe todos os logs (ou especifique os containers)
	${INFO} "[docker] Exibindo logs do container ${args}..."
	@docker-compose ${COMPOSE_FILE} logs -f -t ${args}

key: ## Gera a APP_KEY
	${INFO} "[artisan] Gerando a chave da aplicação..."
	@$(php) php artisan key:generate
	${INFO} "[artisan] Chave gerada com sucesso!"

migrate: ## Migra o banco de dados
	${INFO} "[artisan] Migrando banco de dados..."
	@$(php) php artisan migrate
	${INFO} "[artisan] Migração concluída!"

migrate-rollback: ## Rollback no banco de dados
	${INFO} "[artisan] Rollback no banco de dados..."
	@$(php) php artisan migrate:rollback ${args}
	${INFO} "[artisan] Rollback concluído!"

migration: ## Cria nova migration
	${INFO} "[artisan] Criando migration..."
	@$(php) php artisan make:migration ${args}

seed: ## Roda o seeder
	${INFO} "[artisan] Executando seed no banco de dados..."
	@$(php) php artisan db:seed

seed-class: ## Roda o seeder especificando a classe
	${INFO} "[artisan] Executando seed no banco de dados..."
	@$(php) php artisan db:seed --class=${args}

run-tests: ## Roda os testes unitários
	${INFO} "[artisan] Executando testes..."
	@$(php) php artisan test

redis-monitor: ## Monitora redis
	${INFO} "[docker] Iniciando monitoramento do redis..."
	$(redis) redis-cli MONITOR

composer: ## Executa um comando no composer utilizando os parametros passados
	@$(composer) ${args}

composer-update: ## Atualiza dependências do projeto
	${INFO} "[composer] Atualizando dependências..."
	@$(composer) update --ignore-platform-reqs

composer-update-v: ## Atualiza dependências do projeto (debug)
	${INFO} "[composer] Atualizando dependências (debug)..."
	@$(composer) update --ignore-platform-reqs -vvv

composer-install: ## Instala as dependências do projeto
	${INFO} "[composer] Instalando dependências..."
	$(composer) install --ignore-platform-reqs

composer-require: ## Adiciona uma dependencia ao projeto
	${INFO} "[composer] Adicionando dependência..."
	@$(composer) require ${args} --ignore-platform-reqs

composer-du: ## Faz o dump das classes com o composer
	${INFO} "[composer] Fazendo dump das classes..."
	@$(composer) du --ignore-platform-reqs

yarn-install: ## Instala os packages do package.json
	${INFO} "[yarn] Instalando dependências..."
	@$(yarn) install

yarn-prod: ## Compila os assets para produção
	${INFO} "[yarn] Compilando assets (produção)..."
	@$(yarn) run production

yarn-dev: ## Compila os assets para produção
	${INFO} "[yarn] Compilando assets (dev)..."
	@$(yarn) run dev

yarn-watch: ## Executa watch para desenv
	@$(yarn) run watch

fix-styles: ## Executa phpcs-fixer nos arquivos do projeto
	${INFO} "[phpcs-fixer] Reformatando estilos..."
	@$(php) vendor/bin/php-cs-fixer fix --config=vendor/fuhrmann/phpcs-fixer-laravel/.php_cs.dist --ansi

build: ## Faz o build da imagem
	${INFO} "[docker] Criando imagem..."
	@DOCKER_BUILDKIT=1 \
	docker build \
		--build-arg BUILDKIT_INLINE_CACHE=1 \
		--build-arg INSTALL_XDEBUG=false \
		--build-arg INSTALL_OPCACHE=true \
		--cache-from ${APP_PROJECT_NAME}-php:prod \
		-t ${APP_PROJECT_NAME}-php:prod \
		--target producao .

	@DOCKER_BUILDKIT=1 \
	docker build \
		--build-arg APP_PROJECT_NAME=${APP_PROJECT_NAME} \
		-t ${APP_PROJECT_NAME}-nginx:prod \
		-f ./docker/nginx/Dockerfile .

confirmar:
	@( read -p "Você tem certeza?? [s/N]: " sure && case "$$sure" in [sYS]) true;; *) false;; esac )

help: ## Exibe a ajuda
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

%:
	@:

# Visual
YELLOW := "\e[1;33m"
NC := "\e[0m"

# Mensagem informativa
INFO := @bash -c '\
	printf $(YELLOW); \
	echo "=> $$1"; \
	printf $(NC)' VALUE
