app := docker exec -it api_todos
php := $(app) php
artisan := $(php) artisan

up:
	docker-compose up -d
down:
	docker-compose down
composer:
	composer install --ignore-platform-reqs
env:
	cp .env.example .env
sqlite:
	rm -rf database/database.sqlite
	touch database/database.sqlite
migrations:
	$(artisan) migrate:fresh --seed
storage:
	mkdir storage
passport_install:
	$(artisan) passport:install --force
test:
	$(php) vendor/bin/phpunit
pint:
	$(php) vendor/bin/pint -v --test
bash:
	$(app) /bin/bash
run: up composer env sqlite migrations storage passport_install test