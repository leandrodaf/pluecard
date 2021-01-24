#!/usr/bin/make

SHELL = /bin/sh

CURRENT_USER := $(USER)
CURRENT_UID := $(shell id -u)
CURRENT_GID := $(shell id -g)

export CURRENT_USER
export CURRENT_UID
export CURRENT_GID

.PHONY: up
up:
	docker-compose up -d

.PHONY: build
build:
	docker-compose build

.PHONY: attach
attach: up
	docker-compose exec php ash

.PHONY: stop
stop:
	docker-compose stop

.PHONY: install
install:
	docker-compose run --rm composer install

.PHONY: migrate
migrate:
	docker-compose run --rm artisan migrate

.PHONY: style-fix
style-fix:
	docker-compose run --rm php-cs-fixer --config=.php-cs.dist.php fix --verbose

.PHONY: tests
tests:
	docker-compose run --rm php vendor/bin/phpunit --filter $(filter)

.PHONY: compose-cm
compose-cm:
	docker-compose run --rm composer $(cm)
