up: docker-up
down: docker-down
stop: docker-stop
start: docker-start
restart: docker-restart
build: docker-build
pull: docker-pull
init: docker-down-clear docker-pull docker-build docker-up
init-dev: docker-down-clear docker-pull docker-build-dev docker-up

docker-up:
	docker-compose up --detach --remove-orphans

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down --volumes --remove-orphans

docker-stop:
	docker-compose stop

docker-start:
	docker-compose start

docker-restart:
	docker-compose restart

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

docker-build-dev:
	docker-compose build --build-arg ENV=DEV

shell-php:
	docker-compose run php bash

test:
	docker-compose run php vendor/bin/codecept run

test-with-coverage:
	docker-compose run php vendor/bin/codecept run --coverage --coverage-xml --coverage-html

migrate-test:
	docker-compose run -e DB_NAME='test' php bin/doctrine migrations:migrate -n