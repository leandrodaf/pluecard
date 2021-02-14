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
	docker-compose run --rm php vendor/bin/phpunit

.PHONY: filter-tests
filter-tests:
	docker-compose run --rm php vendor/bin/phpunit --filter $(filter)

.PHONY: compose-cm
compose-cm:
	docker-compose run --rm composer $(cm)

.PHONY: deploy
deploy:
	docker-compose run --rm artisan deploy

.PHONY:
codeclimate: |
	rm -rf storage/app/codeclimate/codeclimate-result.html 
	docker run \
  --interactive --tty --rm \
  --env CODECLIMATE_CODE="$PWD" \
  --volume "$PWD":/code \
  --volume /var/run/docker.sock:/var/run/docker.sock \
  --volume /tmp/cc:/tmp/cc \
  codeclimate/codeclimate analyze -f html app/ >> storage/app/codeclimate/codeclimate-result.html