# Determine if .env file exist
ifneq ("$(wildcard .env)","")
	include .env
	export $(shell sed 's/=.*//' .env)
endif

HOST_UID := $(shell id -u)
HOST_GID := $(shell id -g)
PHP_USER := -u www-data
PR_NAME := -p ${PROJECT_NAME}
INTERACTIVE := $(shell [ -t 0 ] && echo 1)
ERROR_ONLY_FOR_HOST = @printf "\033[33mThis command for host machine\033[39m\n"

ifneq ($(INTERACTIVE), 1)
	OPTION_T := -T
endif

ifeq ($(GITLAB_CI), 1)
	PHPUNIT_OPTIONS := --coverage-text --colors=never
endif

build: ## Build environment
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_HTTP_TEST=$(WEB_PORT_HTTP_TEST) WEB_PORT_HTTPS=$(WEB_PORT_HTTPS) DB_PORT=$(DB_PORT) DB_NAME=$(DB_NAME) DB_PASS=$(DB_PASS) TESTDB_PORT=$(TESTDB_PORT) TESTDB_NAME=$(TESTDB_NAME) TESTDB_PASS=$(TESTDB_PASS) docker compose -f docker-compose.yml build --no-cache

start: ## Start environment
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_HTTP_TEST=$(WEB_PORT_HTTP_TEST) WEB_PORT_HTTPS=$(WEB_PORT_HTTPS) DB_PORT=$(DB_PORT) DB_NAME=$(DB_NAME) DB_PASS=$(DB_PASS) TESTDB_PORT=$(TESTDB_PORT) TESTDB_NAME=$(TESTDB_NAME) TESTDB_PASS=$(TESTDB_PASS) docker compose -f docker-compose.yml $(PR_NAME) up -d

stop: ## Stop environment
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_HTTP_TEST=$(WEB_PORT_HTTP_TEST) WEB_PORT_HTTPS=$(WEB_PORT_HTTPS) DB_PORT=$(DB_PORT) DB_NAME=$(DB_NAME) DB_PASS=$(DB_PASS) TESTDB_PORT=$(TESTDB_PORT) TESTDB_NAME=$(TESTDB_NAME) TESTDB_PASS=$(TESTDB_PASS) docker compose -f docker-compose.yml $(PR_NAME) down

restart: stop start ## Stop and start environment

ssh: ## Get bash inside docker container
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_HTTP_TEST=$(WEB_PORT_HTTP_TEST) WEB_PORT_HTTPS=$(WEB_PORT_HTTPS) DB_PORT=$(DB_PORT) DB_NAME=$(DB_NAME) DB_PASS=$(DB_PASS) TESTDB_PORT=$(TESTDB_PORT) TESTDB_NAME=$(TESTDB_NAME) TESTDB_PASS=$(TESTDB_PASS) docker compose $(PR_NAME) exec $(OPTION_T) $(PHP_USER) web bash

exec: ## Run command inside docker container
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_HTTP_TEST=$(WEB_PORT_HTTP_TEST) WEB_PORT_HTTPS=$(WEB_PORT_HTTPS) DB_PORT=$(DB_PORT) DB_NAME=$(DB_NAME) DB_PASS=$(DB_PASS) TESTDB_PORT=$(TESTDB_PORT) TESTDB_NAME=$(TESTDB_NAME) TESTDB_PASS=$(TESTDB_PASS) docker compose $(PR_NAME) exec $(OPTION_T) $(PHP_USER) web $$cmd

exec-bash:
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_HTTP_TEST=$(WEB_PORT_HTTP_TEST) WEB_PORT_HTTPS=$(WEB_PORT_HTTPS) DB_PORT=$(DB_PORT) DB_NAME=$(DB_NAME) DB_PASS=$(DB_PASS) TESTDB_PORT=$(TESTDB_PORT) TESTDB_NAME=$(TESTDB_NAME) TESTDB_PASS=$(TESTDB_PASS) docker compose $(PR_NAME) exec $(OPTION_T) $(PHP_USER) web bash -c "$(cmd)"

first-run: ## Installs composer dependencies && run migrate
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader && php yii migrate --interactive=0"

composer-install: ## Installs composer dependencies
	@make exec-bash cmd="COMPOSER_MEMORY_LIMIT=-1 composer install --optimize-autoloader"

composer-update: ## Update composer dependencies
	@make exec-bash cmd="composer update"

migrate: ## Migrate
	@make exec-bash cmd="php yii migrate --interactive=0"
