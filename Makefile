export INNODB_USE_NATIVE_AIO=1

ifndef INSIDE_DOCKER_CONTAINER
	INSIDE_DOCKER_CONTAINER = 0
endif

HOST_UID := $(shell id -u)
HOST_GID := $(shell id -g)
PHP_USER := -u www-data
PROJECT_NAME := -p repostator
INTERACTIVE := $(shell [ -t 0 ] && echo 1)
ERROR_ONLY_FOR_HOST = @printf "\033[33mThis command for host machine\033[39m\n"
.DEFAULT_GOAL := help
ifneq ($(INTERACTIVE), 1)
	OPTION_T := -T
endif
ifeq ($(GITLAB_CI), 1)
	PHPUNIT_OPTIONS := --coverage-text --colors=never
endif

help: ## Shows available commands with description
	@echo "\033[34mList of available commands:\033[39m"
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-27s[0m %s\n", $$1, $$2}'

build: ## Build environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) docker compose -f docker-compose.yml build
else
	$(ERROR_ONLY_FOR_HOST)
endif

start: ## Start environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker compose -f docker-compose.yml $(PROJECT_NAME) up -d
else
	$(ERROR_ONLY_FOR_HOST)
endif

stop: ## Stop environment
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker compose -f docker-compose.yml $(PROJECT_NAME) down
else
	$(ERROR_ONLY_FOR_HOST)
endif

restart: stop start ## Stop and start environment

ssh: ## Get bash inside docker container
ifeq ($(INSIDE_DOCKER_CONTAINER), 0)
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) web bash
else
	$(ERROR_ONLY_FOR_HOST)
endif

exec:
ifeq ($(INSIDE_DOCKER_CONTAINER), 1)
	@$$cmd
else
	@HOST_UID=$(HOST_UID) HOST_GID=$(HOST_GID) WEB_PORT_HTTP=$(WEB_PORT_HTTP) WEB_PORT_SSL=$(WEB_PORT_SSL) XDEBUG_CONFIG=$(XDEBUG_CONFIG) INNODB_USE_NATIVE_AIO=$(INNODB_USE_NATIVE_AIO) docker compose $(PROJECT_NAME) exec $(OPTION_T) $(PHP_USER) web $$cmd
endif
